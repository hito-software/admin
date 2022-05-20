<?php

namespace Hito\Admin\Http\Controllers;

use Hito\Admin\Http\Controllers\Controller;
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
    public function __construct(private RoleService $roleService)
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
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate(request(), [
            'name' => [
                'required'
            ],
            'description' => 'max:255',
            'type' => 'required',
            'required' => 'required|boolean',
        ]);

        $role = $this->roleService->create(request('type'), request('name'), request('description'), request('required'), auth()->id());

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
     * @param Request $request
     * @param Role $role
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        $data = $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('teams')->ignoreModel($role)
            ],
            'description' => 'max:255',
            'required' => 'required|boolean'
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
