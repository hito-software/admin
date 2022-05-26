<?php

namespace Hito\Admin\Http\Controllers;

use Hito\Admin\Factories\AdminResourceFactory;
use Hito\Admin\Http\Requests\StoreGroupRequest;
use Hito\Admin\Http\Requests\UpdateGroupRequest;
use Hito\Platform\Models\Group;
use Hito\Platform\Services\GroupService;
use Hito\Platform\Services\PermissionService;
use Hito\Platform\Services\UserService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use function request;
use function view;

class GroupController extends Controller
{
    private string $entitySingular = 'Group';
    private string $entityPlural = 'Groups';

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
        $groups = $this->groupService->getAll(); // TODO Replace with paginator

        return AdminResourceFactory::index($groups, function (Group $group) {
            return view('hito-admin::groups._index-item', compact('group'));
        })
            ->entity($this->entitySingular, $this->entityPlural)
            ->createUrl(route('admin.groups.create'))
            ->showUrl(function (Group $group) {
                if (!auth()->user()->can('show', $group)) {
                    return null;
                }

                return route('admin.groups.show', $group->id);
            })
            ->editUrl(function (Group $group) {
                if (!auth()->user()->can('edit', $group)) {
                    return null;
                }

                return route('admin.groups.edit', $group->id);
            })
            ->deleteUrl(function (Group $group) {
                if (!auth()->user()->can('delete', $group)) {
                    return null;
                }

                return route('admin.groups.delete', $group->id);
            })
            ->build();
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

        return AdminResourceFactory::create()
            ->entity($this->entitySingular, $this->entityPlural)
            ->storeUrl(route('admin.groups.store'))
            ->view(view('hito-admin::groups._form', compact('group', 'users', 'permissions')))
            ->build();
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

        return AdminResourceFactory::store('admin.groups.edit', $group->id)
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }

    /**
     * @param Group $group
     * @return View
     */
    public function show(Group $group): View
    {
        $users = $this->userService->getAll()->map(fn($user) => [
            'value' => $user->id,
            'label' => "{$user->name} {$user->surname}"
        ])->toArray();

        $permissions = $this->permissionService->getAll()->map(fn($permission) => [
            'value' => $permission->id,
            'label' => $permission->name
        ])->toArray();

        return AdminResourceFactory::show()
            ->entity($this->entitySingular, $this->entityPlural)
            ->title($group->name)
            ->view(view('hito-admin::groups._show', compact('group', 'users', 'permissions')))
            ->editUrl(route('admin.groups.edit', $group->id))
            ->deleteUrl(route('admin.groups.delete', $group->id))
            ->indexUrl(route('admin.groups.index'))
            ->build();
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

        return AdminResourceFactory::edit()
            ->entity($this->entitySingular, $this->entityPlural)
            ->updateUrl(route('admin.groups.update', compact('group')))
            ->view(view('hito-admin::groups._form', compact('group', 'users', 'permissions')))
            ->build();
    }

    /**
     * @param UpdateGroupRequest $request
     * @param Group $group
     * @return RedirectResponse
     */
    public function update(UpdateGroupRequest $request, Group $group): RedirectResponse
    {
        $this->groupService->update($group->id, request(['name', 'description', 'users', 'permissions']));

        return AdminResourceFactory::update()
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }

    /**
     * @param Group $group
     * @return View
     * @throws AuthorizationException
     */
    public function delete(Group $group): View
    {
        $this->authorize('delete', $group);

        return AdminResourceFactory::delete()
            ->entity($this->entitySingular, $this->entityPlural)
            ->isUsed(false)
            ->destroyUrl(route('admin.groups.destroy', compact('group')))
            ->cancelUrl(route('admin.groups.show', $group->id))
            ->build();
    }

    /**
     * @param Group $group
     * @return RedirectResponse
     */
    public function destroy(Group $group): RedirectResponse
    {
        $group->delete();

        return AdminResourceFactory::destroy('admin.groups.index')
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }
}
