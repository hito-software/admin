<?php

namespace Hito\Admin\Http\Controllers;

use Hito\Admin\Http\Controllers\Controller;
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
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProjectController extends Controller
{
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

        return view('hito-admin::projects.index', compact('projects'));
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

        return view('hito-admin::projects.create', compact('project', 'clients', 'countries', 'roles', 'users', 'teams'));
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
            request('description'),
            auth()->id()
        );

        $this->validateRoles($request);

        $this->syncRoles($request, $project);

        $teams = $this->teamService->getByIds(request('teams', []))->pluck('id')->toArray();

        $this->projectService->syncTeams($project->id, $teams);

        return redirect()->route('admin.projects.edit', $project->id)
            ->with('success', \Lang::get('forms.created_successfully', ['entity' => 'Project']));
    }

    /**
     * @param Project $project
     * @return View
     */
    public function show(Project $project): View
    {
        $client = $project->client;
        $country = $project->country;
        $roles = $this->roleService->getAllByType('project');
        $members = $project->members;
        $teams = $project->teams;

        return view('hito-admin::projects.show', compact('project', 'client', 'country', 'roles',
            'members', 'teams'));
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

        return view('hito-admin::projects.edit', compact('project', 'clients', 'countries', 'roles',
            'members', 'users', 'teams'));
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

        return back()->with('success', \Lang::get('forms.updated_successfully', ['entity' => 'Project']));
    }

    /**
     * @param Project $project
     * @return View
     * @throws AuthorizationException
     */
    public function delete(Project $project): View
    {
        $this->authorize('delete', $project);

        return view('shared.delete-entity', [
            'action' => route('admin.projects.destroy', $project->id),
            'noAction' => route('admin.projects.show', $project->id),
            'entity' => 'project'
        ]);
    }

    /**
     * @param Project $project
     * @return RedirectResponse
     */
    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()->route('admin.projects.index')->with('sucess', 'Project deleted successfully.');
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

