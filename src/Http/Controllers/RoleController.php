<?php

namespace Hito\Admin\Http\Controllers;

use Hito\Admin\Http\Controllers\Controller;
use Hito\Admin\Http\Requests\StoreRoleRequest;
use Hito\Admin\Http\Requests\UpdateRoleRequest;
use Hito\Platform\Models\Role;
use Hito\Platform\Services\RoleService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
{
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

        return view('hito-admin::roles.index', compact('roles', 'type'));
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

        return view('hito-admin::roles.create', compact('role', 'type'));
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

        return redirect()->route('admin.roles.edit', $role->id)
            ->with('success', \Lang::get('forms.created_successfully', ['entity' => 'Project']));
    }

    /**
     * @param Role $role
     * @return View
     */
    public function show(Role $role): View
    {
        return view('hito-admin::roles.show', compact('role'));
    }

    /**
     * @param Request $request
     * @param Role $role
     * @return View
     */
    public function edit(Request $request, Role $role): View
    {
        $type = $request->query('type');

        return view('hito-admin::roles.edit', compact('role', 'type'));
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
        return back()->with('success', \Lang::get('forms.updated_successfully', ['entity' => 'Role']));
    }

    /**
     * @param Role $role
     * @return View
     * @throws AuthorizationException
     */
    public function delete(Role $role): View
    {
        $this->authorize('delete', $role);

        return view('shared.delete-entity', [
            'action' => route('admin.roles.destroy', $role->id),
            'noAction' => route('admin.roles.show', $role->id),
            'entity' => 'role'
        ]);
    }

    /**
     * @param Role $role
     * @return RedirectResponse
     */
    public function destroy(Role $role): RedirectResponse
    {
        $type = $this->roleService->mapClassTypeToType($role->entity_type);
        $role->delete();

        return redirect()->route('admin.roles.index', compact('type'))
            ->with('success', \Lang::get('forms.deleted_successfully', ['entity' => 'Role']));
    }
}
