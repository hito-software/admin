<?php

namespace Hito\Admin\Http\Controllers;

use Hito\Admin\Http\Controllers\Controller;
use Hito\Platform\Models\Team;
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

class TeamController extends Controller
{
    public function __construct(
        private TeamService    $teamService,
        private UserService    $userService,
        private RoleService    $roleService,
        private ProjectService $projectService
    ) {
        $this->authorizeResource(Team::class);
    }

    /**
     * @return View
     */
    public function index(): View
    {
        $teams = $this->teamService->getAllPaginated();

        return view('hito-admin::teams.index', compact('teams'));
    }

    /**
     * @param Team $team
     * @return View
     */
    public function create(Team $team): View
    {
        $users = $this->userService->getAll()->map(fn ($user) => [
            'value' => $user->id,
            'label' => "{$user->name} {$user->surname}"
        ])->toArray();
        $roles = $this->roleService->getAllByType('team');
        $projects = $this->projectService->getAll()->map(fn ($project) => [
            'value' => $project->id,
            'label' => $project->name
        ])->toArray();

        return view('hito-admin::teams.create', compact('team', 'projects', 'roles', 'users'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request, Team $team): RedirectResponse
    {
        $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('teams')->ignoreModel($team)->withoutTrashed()
            ],
            'description' => 'max:255',
            'projects' => 'nullable|array',
            'projects.*' => 'uuid'
        ]);

        $team = $this->teamService->create(request('name'), request('description'), auth()->id());

        $this->validateRoles($request);

        $this->syncRoles($request, $team);

        $this->teamService->syncProjects($team->id, request('projects', []));

        return redirect()->route('admin.teams.edit', $team->id)
            ->with('success', \Lang::get('forms.created_successfully', ['entity' => 'Team']));
    }

    /**
     * @param Team $team
     * @return View
     */
    public function show(Team $team): View
    {
        $roles = $this->roleService->getAllByType('team');
        $members = $team->members;
        $projects = $team->projects;

        return view('hito-admin::teams.show', compact('team', 'roles', 'members', 'projects'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Team $team
     * @return View
     */
    public function edit(Team $team): View
    {
        $users = $this->userService->getAll()->map(fn ($user) => [
            'value' => $user->id,
            'label' => "{$user->name} {$user->surname}"
        ])->toArray();

        $roles = $this->roleService->getAllByType('team');
        $members = $this->teamService->getMembersByTeamId($team->id);

        $projects = $this->projectService->getAll()->map(fn ($project) => [
            'value' => $project->id,
            'label' => $project->name
        ])->toArray();

        return view('hito-admin::teams.edit', compact('team', 'users', 'roles', 'members', 'projects'));
    }

    /**
     * @param Request $request
     * @param Team $team
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, Team $team): RedirectResponse
    {
        $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('teams')->ignoreModel($team, 'name')->withoutTrashed()
            ],
            'description' => 'max:255',
            'projects' => 'nullable|array',
            'projects.*' => 'uuid'
        ]);

        $this->validateRoles($request);

        $data = request()->only(['name', 'description']);
        $data['user_id'] = auth()->id();

        $this->syncRoles($request, $team);

        $projects = $this->projectService->getByIds(request('projects', []))->pluck('id')->toArray();
        $this->teamService->syncProjects($team->id, $projects);

        $this->teamService->update($team->id, $data);
        return back()->with('success', \Lang::get('forms.updated_successfully', ['entity' => 'Team']));
    }

    /**
     * @param Team $team
     * @return View
     * @throws AuthorizationException
     */
    public function delete(Team $team): View
    {
        $this->authorize('delete', $team);

        return view('shared.delete-entity', [
            'action' => route('admin.teams.destroy', $team->id),
            'noAction' => route('admin.teams.show', $team->id),
            'entity' => 'team'
        ]);
    }

    /**
     * @param Team $team
     * @return RedirectResponse
     */
    public function destroy(Team $team): RedirectResponse
    {
        $team->delete();

        return redirect()->route('admin.teams.index')
            ->with('success', \Lang::get('forms.deleted_successfully', ['entity' => 'Team']));
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
        $roles = array_filter($request->all(), fn ($key) => str_contains($key, 'roles_'), ARRAY_FILTER_USE_KEY);
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
