<?php

namespace Hito\Admin\Http\Controllers;

use Hito\Admin\Http\Controllers\Controller;
use Hito\Admin\Http\Requests\StoreDepartmentRequest;
use Hito\Admin\Http\Requests\UpdateDepartmentRequest;
use Hito\Platform\Models\Department;
use Hito\Platform\Services\DepartmentService;
use Hito\Platform\Services\UserService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class DepartmentController extends Controller
{
    public function __construct(
        private readonly DepartmentService $departmentService,
        private readonly UserService       $userService)
    {
        $this->authorizeResource(Department::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $departments = $this->departmentService->getAllPaginated();

        return view('hito-admin::departments.index', compact('departments'));
    }

    /**
     * @param Department $department
     * @return View
     */
    public function create(Department $department): View
    {
        $users = $this->userService->getAll()->map(fn($user) => [
            'value' => $user->id,
            'label' => "{$user->name} {$user->surname}"
        ])->toArray();

        return view('hito-admin::departments.create', compact('department', 'users'));
    }

    /**
     * @param StoreDepartmentRequest $request
     * @param Department $department
     * @return RedirectResponse
     */
    public function store(StoreDepartmentRequest $request, Department $department): RedirectResponse
    {
        $department = $this->departmentService->create(
            request('name'),
            request('description'),
            request('members', [])
        );

        return redirect()->route('admin.departments.edit', $department->id)
            ->with('success', \Lang::get('forms.created_successfully', ['entity' => 'Department']));
    }

    /**
     * Display the specified resource.
     *
     * @param Department $department
     * @return View
     */
    public function show(Department $department): View
    {
        $users = $department->users;

        return view('hito-admin::departments.show', compact('department', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Department $department
     * @return View
     */
    public function edit(Department $department): View
    {
        $users = $this->userService->getAll()->map(fn($user) => [
            'value' => $user->id,
            'label' => "{$user->name} {$user->surname}"
        ])->toArray();

        return view('hito-admin::departments.edit', compact('department', 'users'));
    }

    /**
     * @param UpdateDepartmentRequest $request
     * @param Department $department
     * @return RedirectResponse
     */
    public function update(UpdateDepartmentRequest $request, Department $department): RedirectResponse
    {
        $data = request(['name', 'description', 'members']);

        $this->departmentService->update($department->id, $data);

        return redirect()->back()->with('success', \Lang::get('forms.updated_successfully', ['entity' => 'Department']));
    }

    /**
     * @param Department $department
     * @return View
     * @throws AuthorizationException
     */
    public function delete(Department $department): View
    {
        $this->authorize('delete', $department);

        return view('shared.delete-entity', [
            'action' => route('admin.departments.destroy', $department->id),
            'noAction' => route('admin.departments.show', $department->id),
            'entity' => 'department'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Department $department
     * @return RedirectResponse
     */
    public function destroy(Department $department): RedirectResponse
    {
        $department->delete();

        return redirect()->route('admin.departments.index')
            ->with('sucess', \Lang::get('forms.deleted_successfully', ['entity' => 'Department']));
    }
}
