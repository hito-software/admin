<?php

namespace Hito\Admin\Http\Controllers;

use Hito\Admin\Http\Controllers\Controller;
use Hito\Admin\Http\Requests\StoreGroupRequest;
use Hito\Admin\Http\Requests\UpdateGroupRequest;
use Hito\Platform\Models\Group;
use Hito\Platform\Services\GroupService;
use Hito\Platform\Services\PermissionService;
use Hito\Platform\Services\UserService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use function back;
use function redirect;
use function request;
use function view;

class GroupController extends Controller
{
    public function __construct(
        private readonly GroupService      $groupService,
        private readonly UserService       $userService,
        private readonly PermissionService $permissionService)
    {
        $this->authorizeResource(Group::class);
    }

    /**
     * @return View
     */
    public function index(): View
    {
        $groups = $this->groupService->getAll();

        return view('hito-admin::groups.index', compact('groups'));
    }

    /**
     * @param Group $group
     * @return View
     */
    public function create(Group $group): View
    {
        $users = $this->userService->getAll()->map(fn($user) => [
            'value' => $user->id,
            'label' => "{$user->name} {$user->surname}"
        ])->toArray();

        $permissions = $this->permissionService->getAll()->map(fn($permission) => [
            'value' => $permission->id,
            'label' => $permission->name
        ])->toArray();

        return view('hito-admin::groups.create', compact('group', 'users', 'permissions'));
    }

    /**
     * @param StoreGroupRequest $request
     * @return RedirectResponse
     */
    public function store(StoreGroupRequest $request): RedirectResponse
    {
        $group = $this->groupService->create(
            request('name'),
            request('description'),
            request('users', []),
            request('permission', [])
        );

        return redirect()->route('admin.groups.edit', $group->id)
            ->with('success', \Lang::get('forms.created_successfully', ['entity' => 'Group']));
    }

    /**
     * @param Group $group
     * @return View
     */
    public function show(Group $group): View
    {
        $users = $group->users;
        $permissions = $group->permissions;

        return view('hito-admin::groups.show', compact('group', 'users', 'permissions'));
    }

    /**
     * @param Group $group
     * @return View
     */
    public function edit(Group $group): View
    {
        $users = $this->userService->getAll()->map(fn($user) => [
            'value' => $user->id,
            'label' => "{$user->name} {$user->surname}"
        ])->toArray();

        $permissions = $this->permissionService->getAll()->map(fn($permission) => [
            'value' => $permission->id,
            'label' => $permission->name
        ])->toArray();

        return view('hito-admin::groups.edit', compact('group', 'users', 'permissions'));
    }

    /**
     * @param UpdateGroupRequest $request
     * @param Group $group
     * @return RedirectResponse
     */
    public function update(UpdateGroupRequest $request, Group $group): RedirectResponse
    {
        $this->groupService->update($group->id, request(['name', 'description', 'users', 'permissions']));
        return back()->with('success', \Lang::get('forms.updated_successfully', ['entity' => 'Group']));
    }

    /**
     * @param Group $group
     * @return View
     * @throws AuthorizationException
     */
    public function delete(Group $group): View
    {
        $this->authorize('delete', $group);

        return view('shared.delete-entity', [
            'action' => route('admin.groups.destroy', $group->id),
            'noAction' => route('admin.groups.show', $group->id),
            'entity' => 'group'
        ]);
    }

    /**
     * @param Group $group
     * @return RedirectResponse
     */
    public function destroy(Group $group): RedirectResponse
    {
        $group->delete();

        return redirect()->route('admin.groups.index')
            ->with('success', \Lang::get('forms.deleted_successfully', ['entity' => 'Group']));
    }
}
