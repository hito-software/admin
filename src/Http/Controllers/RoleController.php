<?php

namespace Hito\Admin\Http\Controllers;

use Hito\Admin\Factories\AdminResourceFactory;
use Hito\Admin\Http\Requests\StoreRoleRequest;
use Hito\Admin\Http\Requests\UpdateRoleRequest;
use Hito\Platform\Models\Role;
use Hito\Platform\Services\RoleService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
{
    private string $entitySingular = 'Role';
    private string $entityPlural = 'Roles';

    public function __construct(private readonly RoleService $roleService)
    {
        $this->authorizeResource(Role::class);
    }

    /**
     * @return View
     * @throws ValidationException
     */
    public function index(): View
    {
        $this->validate(request(), [
            'type' => 'required|in:team,project'
        ]);
        $type = request('type');

        $roles = $this->roleService->getAllByType($type);

        return AdminResourceFactory::index($roles, function (Role $role) {
            return view('hito-admin::roles._index-item', compact('role'));
        })
            ->entity($this->entitySingular, $this->entityPlural)
            ->createUrl(route('admin.roles.create', compact('type')))
            ->showUrl(function (Role $role) {
                if (!auth()->user()->can('show', $role)) {
                    return null;
                }

                return route('admin.roles.show', $role->id);
            })
            ->editUrl(function (Role $role) {
                if (!auth()->user()->can('edit', $role)) {
                    return null;
                }

                return route('admin.roles.edit', $role->id);
            })
            ->deleteUrl(function (Role $role) {
                if (!auth()->user()->can('delete', $role)) {
                    return null;
                }

                return route('admin.roles.delete', $role->id);
            })
            ->build();
    }

    /**
     * @param Request $request
     * @param Role $role
     * @return View|RedirectResponse
     */
    public function create(Request $request, Role $role): View|RedirectResponse
    {
        $type = $request->query('type');
        if (empty($type)) {
            return redirect()->route('admin.dashboard');
        }

        return AdminResourceFactory::create()
            ->entity($this->entitySingular, $this->entityPlural)
            ->storeUrl(route('admin.roles.store'))
            ->view(view('hito-admin::roles._form', compact('role', 'type')))
            ->build();
    }

    /**
     * @param StoreRoleRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $role = $this->roleService->create(
            request('type'),
            request('name'),
            request('description'),
            request('required')
        );

        return AdminResourceFactory::store('admin.roles.edit', $role->id)
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }

    /**
     * @param Role $role
     * @return View
     */
    public function show(Role $role): View
    {
        $type = $this->roleService->mapClassTypeToType($role->entity_type);

        return AdminResourceFactory::show()
            ->entity($this->entitySingular, $this->entityPlural)
            ->title($role->name)
            ->view(view('hito-admin::roles._show', compact('role')))
            ->editUrl(route('admin.roles.edit', $role->id))
            ->deleteUrl(route('admin.roles.delete', $role->id))
            ->indexUrl(route('admin.roles.index', compact('type')))
            ->build();
    }

    /**
     * @param Request $request
     * @param Role $role
     * @return View
     */
    public function edit(Request $request, Role $role): View
    {
        $type = $request->query('type');

        return AdminResourceFactory::edit()
            ->entity($this->entitySingular, $this->entityPlural)
            ->updateUrl(route('admin.roles.update', compact('role')))
            ->view(view('hito-admin::roles._form', compact('role', 'type')))
            ->build();
    }

    /**
     * @param UpdateRoleRequest $request
     * @param Role $role
     * @return RedirectResponse
     */
    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $data = $request->only([
            'name',
            'description',
            'required'
        ]);

        $this->roleService->update($role->id, $data);

        return AdminResourceFactory::update()
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }

    /**
     * @param Role $role
     * @return View
     * @throws AuthorizationException
     */
    public function delete(Role $role): View
    {
        $this->authorize('delete', $role);

        return AdminResourceFactory::delete()
            ->entity($this->entitySingular, $this->entityPlural)
            ->isUsed(false)
            ->destroyUrl(route('admin.roles.destroy', compact('role')))
            ->cancelUrl(route('admin.roles.show', $role->id))
            ->build();
    }

    /**
     * @param Role $role
     * @return RedirectResponse
     */
    public function destroy(Role $role): RedirectResponse
    {
        $type = $this->roleService->mapClassTypeToType($role->entity_type);
        $role->delete();

        return AdminResourceFactory::destroy('admin.locations.index', compact('type'))
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }
}
