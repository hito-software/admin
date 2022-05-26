<?php

namespace Hito\Admin\Http\Controllers;

use Hito\Admin\Factories\AdminResourceFactory;
use Hito\Admin\Http\Requests\StoreProjectRequest;
use Hito\Admin\Http\Requests\UpdateProjectRequest;
use Hito\Platform\Models\Project;
use Hito\Platform\Services\ClientService;
use Hito\Platform\Services\CountryService;
use Hito\Platform\Services\ProjectService;
use Hito\Platform\Services\RoleService;
use Hito\Platform\Services\TeamService;
use Hito\Platform\Services\UserService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProjectController extends Controller
{
    private string $entitySingular = 'Project';
    private string $entityPlural = 'Projects';

    public function __construct(
        private readonly ProjectService $projectService,
        private readonly ClientService  $clientService,
        private readonly CountryService $countryService,
        private readonly RoleService    $roleService,
        private readonly UserService    $userService,
        private readonly TeamService    $teamService)
    {
        $this->authorizeResource(Project::class);
    }

    /**
     * @return View
     */
    public function index(): View
    {
        $projects = $this->projectService->getAllPaginated();

        return AdminResourceFactory::index($projects, function (Project $project) {
            return view('hito-admin::projects._index-item', compact('project'));
        })
            ->entity($this->entitySingular, $this->entityPlural)
            ->createUrl(route('admin.projects.create'))
            ->showUrl(function (Project $project) {
                if (!auth()->user()->can('show', $project)) {
                    return null;
                }

                return route('admin.projects.show', $project->id);
            })
            ->editUrl(function (Project $project) {
                if (!auth()->user()->can('edit', $project)) {
                    return null;
                }

                return route('admin.projects.edit', $project->id);
            })
            ->deleteUrl(function (Project $project) {
                if (!auth()->user()->can('delete', $project)) {
                    return null;
                }

                return route('admin.projects.delete', $project->id);
            })
            ->build();
    }

    /**
     * @param Project $project
     * @return View
     */
    public function create(Project $project): View
    {
        $clients = $this->clientService->getAll()->map(fn($client) => [
            'value' => $client->id,
            'label' => $client->name
        ])->toArray();

        $countries = $this->countryService->getAll()->map(fn($country) => [
            'value' => $country->id,
            'label' => $country->name
        ])->toArray();

        $roles = $this->roleService->getAllByType('project');
        $users = $this->userService->getAll()->map(fn($user) => [
            'value' => $user->id,
            'label' => "{$user->name} {$user->surname}"
        ])->toArray();

        $teams = $this->teamService->getAll()->map(fn($team) => [
            'value' => $team->id,
            'label' => $team->name
        ])->toArray();

        return AdminResourceFactory::create()
            ->entity($this->entitySingular, $this->entityPlural)
            ->storeUrl(route('admin.projects.store'))
            ->view(view('hito-admin::projects._form', compact('project', 'clients', 'countries', 'roles', 'users', 'teams')))
            ->build();
    }

    /**
     * @param StoreProjectRequest $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $project = $this->projectService->create(
            request('name'),
            request('client'),
            request('country'),
            request('address'),
            request('team'),
            request('description')
        );

        $this->validateRoles($request);

        $this->syncRoles($request, $project);

        $teams = $this->teamService->getByIds(request('teams', []))->pluck('id')->toArray();

        $this->projectService->syncTeams($project->id, $teams);

        return AdminResourceFactory::store('admin.projects.edit', $project->id)
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }

    /**
     * @param Project $project
     * @return View
     */
    public function show(Project $project): View
    {
        $clients = $this->clientService->getAll()->map(fn($client) => [
            'value' => $client->id,
            'label' => $client->name
        ])->toArray();

        $countries = $this->countryService->getAll()->map(fn($country) => [
            'value' => $country->id,
            'label' => $country->name
        ])->toArray();

        $roles = $this->roleService->getAllByType('project');

        $users = $this->userService->getAll()->map(fn($user) => [
            'value' => $user->id,
            'label' => "{$user->name} {$user->surname}"
        ])->toArray();

        $members = $this->projectService->getMembersByProjectId($project->id);

        $teams = $this->teamService->getAll()->map(fn($team) => [
            'value' => $team->id,
            'label' => $team->name
        ])->toArray();

        return AdminResourceFactory::show()
            ->entity($this->entitySingular, $this->entityPlural)
            ->title($project->name)
            ->view(view('hito-admin::projects._show', compact('project', 'clients', 'countries', 'roles',
                'members', 'users', 'teams')))
            ->editUrl(route('admin.projects.edit', $project->id))
            ->deleteUrl(route('admin.projects.delete', $project->id))
            ->indexUrl(route('admin.projects.index'))
            ->build();
    }

    /**
     * @param Project $project
     * @return View
     */
    public function edit(Project $project): View
    {
        $clients = $this->clientService->getAll()->map(fn($client) => [
            'value' => $client->id,
            'label' => $client->name
        ])->toArray();

        $countries = $this->countryService->getAll()->map(fn($country) => [
            'value' => $country->id,
            'label' => $country->name
        ])->toArray();

        $roles = $this->roleService->getAllByType('project');

        $users = $this->userService->getAll()->map(fn($user) => [
            'value' => $user->id,
            'label' => "{$user->name} {$user->surname}"
        ])->toArray();

        $members = $this->projectService->getMembersByProjectId($project->id);

        $teams = $this->teamService->getAll()->map(fn($team) => [
            'value' => $team->id,
            'label' => $team->name
        ])->toArray();

        return AdminResourceFactory::edit()
            ->entity($this->entitySingular, $this->entityPlural)
            ->updateUrl(route('admin.projects.update', compact('project')))
            ->view(view('hito-admin::projects._form', compact('project', 'clients', 'countries', 'roles',
                'members', 'users', 'teams')))
            ->build();
    }

    /**
     * @param UpdateProjectRequest $request
     * @param Project $project
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $this->validateRoles($request);

        $data = request()->only(['name', 'description', 'address']);
        $data['country_id'] = request('country');
        $data['client_id'] = request('client');

        $this->syncRoles($request, $project);

        $teams = $this->teamService->getByIds(request('teams', []))->pluck('id')->toArray();

        $this->projectService->syncTeams($project->id, $teams);

        $this->projectService->update($project->id, $data);

        return AdminResourceFactory::update()
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }

    /**
     * @param Project $project
     * @return View
     * @throws AuthorizationException
     */
    public function delete(Project $project): View
    {
        $this->authorize('delete', $project);

        return AdminResourceFactory::delete()
            ->entity($this->entitySingular, $this->entityPlural)
            ->isUsed(false)
            ->destroyUrl(route('admin.projects.destroy', compact('project')))
            ->cancelUrl(route('admin.projects.show', $project->id))
            ->build();
    }

    /**
     * @param Project $project
     * @return RedirectResponse
     */
    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return AdminResourceFactory::destroy('admin.projects.index')
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }

    /**
     * @param Request $request
     * @return void
     * @throws ValidationException
     */
    private function validateRoles(Request $request): void
    {
        $requiredRoles = $this->roleService->getRequiredByType(Project::class)->pluck('id')->toArray();
        $validationRules = [];

        $errors = [];

        foreach ($requiredRoles as $id) {
            $key = "roles_{$id}";

            $validationRules[$key] = 'required|array';
            $validationRules["{$key}.*"] = 'uuid';
            $errors["{$key}.required"] = 'This role requires at least one member.';
        }

        $this->validate($request, $validationRules, $errors);
    }

    /**
     * @param Request $request
     * @param Project $project
     * @return void
     */
    private function syncRoles(Request $request, Project $project): void
    {
        $roles = array_filter($request->all(), fn($key) => str_contains($key, 'roles_'), ARRAY_FILTER_USE_KEY);
        $members = [];

        foreach ($roles as $key => $userUuids) {
            $roleUuid = str_replace('roles_', '', $key);

            foreach ($userUuids as $userUuid) {
                $members[] = [
                    'user_id' => $userUuid,
                    'role_id' => $roleUuid
                ];
            }
        }

        $this->projectService->syncMembers($project->id, $members);
    }
}

