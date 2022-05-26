<?php

namespace Hito\Admin\Http\Controllers;

use Hito\Admin\Factories\AdminResourceFactory;
use Hito\Admin\Http\Requests\StoreTeamRequest;
use Hito\Admin\Http\Requests\UpdateTeamRequest;
use Hito\Platform\Models\Team;
use Hito\Platform\Services\ProjectService;
use Hito\Platform\Services\RoleService;
use Hito\Platform\Services\TeamService;
use Hito\Platform\Services\UserService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TeamController extends Controller
{
    private string $entitySingular = 'Team';
    private string $entityPlural = 'Teams';

    public function __construct(
        private readonly TeamService    $teamService,
        private readonly UserService    $userService,
        private readonly RoleService    $roleService,
        private readonly ProjectService $projectService
    )
    {
        $this->authorizeResource(Team::class);
    }

    /**
     * @return View
     */
    public function index(): View
    {
        $teams = $this->teamService->getAllPaginated();

        return AdminResourceFactory::index($teams, function (Team $team) {
            return view('hito-admin::teams._index-item', compact('team'));
        })
            ->entity($this->entitySingular, $this->entityPlural)
            ->createUrl(route('admin.teams.create'))
            ->showUrl(function (Team $team) {
                if (!auth()->user()->can('show', $team)) {
                    return null;
                }

                return route('admin.teams.show', $team->id);
            })
            ->editUrl(function (Team $team) {
                if (!auth()->user()->can('edit', $team)) {
                    return null;
                }

                return route('admin.teams.edit', $team->id);
            })
            ->deleteUrl(function (Team $team) {
                if (!auth()->user()->can('delete', $team)) {
                    return null;
                }

                return route('admin.teams.delete', $team->id);
            })
            ->action('Manage roles', route('admin.roles.index', ['type' => 'team']), 'fas fa-users')
            ->build();
    }

    /**
     * @param Team $team
     * @return View
     */
    public function create(Team $team): View
    {
        $users = $this->userService->getAll()->map(fn($user) => [
            'value' => $user->id,
            'label' => "{$user->name} {$user->surname}"
        ])->toArray();
        $roles = $this->roleService->getAllByType('team');
        $projects = $this->projectService->getAll()->map(fn($project) => [
            'value' => $project->id,
            'label' => $project->name
        ])->toArray();
        $members = collect(); // @phpstan-ignore-line

        return AdminResourceFactory::create()
            ->entity($this->entitySingular, $this->entityPlural)
            ->storeUrl(route('admin.teams.store'))
            ->view(view('hito-admin::teams._form', compact('team', 'projects', 'roles', 'users', 'members')))
            ->build();
    }

    /**
     * @param StoreTeamRequest $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(StoreTeamRequest $request): RedirectResponse
    {
        $this->validateRoles($request);

        $team = $this->teamService->create(
            request('name'),
            request('description')
        );

        $this->syncRoles($request, $team);

        $this->teamService->syncProjects($team->id, request('projects', []));

        return AdminResourceFactory::store('admin.teams.edit', $team->id)
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }

    /**
     * @param Team $team
     * @return View
     */
    public function show(Team $team): View
    {
        $users = $this->userService->getAll()->map(fn($user) => [
            'value' => $user->id,
            'label' => "{$user->name} {$user->surname}"
        ])->toArray();

        $roles = $this->roleService->getAllByType('team');
        $members = $this->teamService->getMembersByTeamId($team->id);

        $projects = $this->projectService->getAll()->map(fn($project) => [
            'value' => $project->id,
            'label' => $project->name
        ])->toArray();

        return AdminResourceFactory::show()
            ->entity($this->entitySingular, $this->entityPlural)
            ->title($team->name)
            ->view(view('hito-admin::teams._show', compact('team', 'users', 'roles', 'members', 'projects')))
            ->editUrl(route('admin.teams.edit', $team->id))
            ->deleteUrl(route('admin.teams.delete', $team->id))
            ->indexUrl(route('admin.teams.index'))
            ->build();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Team $team
     * @return View
     */
    public function edit(Team $team): View
    {
        $users = $this->userService->getAll()->map(fn($user) => [
            'value' => $user->id,
            'label' => "{$user->name} {$user->surname}"
        ])->toArray();

        $roles = $this->roleService->getAllByType('team');
        $members = $this->teamService->getMembersByTeamId($team->id);

        $projects = $this->projectService->getAll()->map(fn($project) => [
            'value' => $project->id,
            'label' => $project->name
        ])->toArray();

        return AdminResourceFactory::edit()
            ->entity($this->entitySingular, $this->entityPlural)
            ->updateUrl(route('admin.teams.update', compact('team')))
            ->view(view('hito-admin::teams._form', compact('team', 'users', 'roles', 'members', 'projects')))
            ->build();
    }

    /**
     * @param UpdateTeamRequest $request
     * @param Team $team
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(UpdateTeamRequest $request, Team $team): RedirectResponse
    {
        $this->validateRoles($request);

        $data = request()->only(['name', 'description']);
        $data['user_id'] = auth()->id();

        $this->syncRoles($request, $team);

        $projects = $this->projectService->getByIds(request('projects', []))->pluck('id')->toArray();
        $this->teamService->syncProjects($team->id, $projects);

        $this->teamService->update($team->id, $data);

        return AdminResourceFactory::update()
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }

    /**
     * @param Team $team
     * @return View
     * @throws AuthorizationException
     */
    public function delete(Team $team): View
    {
        $this->authorize('delete', $team);

        return AdminResourceFactory::delete()
            ->entity($this->entitySingular, $this->entityPlural)
            ->isUsed(false)
            ->destroyUrl(route('admin.teams.destroy', compact('team')))
            ->cancelUrl(route('admin.teams.show', $team->id))
            ->build();
    }

    /**
     * @param Team $team
     * @return RedirectResponse
     */
    public function destroy(Team $team): RedirectResponse
    {
        $team->delete();

        return AdminResourceFactory::destroy('admin.teams.index')
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
        $requiredRoles = $this->roleService->getRequiredByType(Team::class)->pluck('id')->toArray();
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
     * @param Team $team
     * @return void
     */
    private function syncRoles(Request $request, Team $team): void
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

        $this->teamService->syncMembers($team->id, $members);
    }
}
