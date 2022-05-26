<?php

namespace Hito\Admin\Http\Controllers;

use Hito\Admin\Factories\AdminResourceFactory;
use Hito\Admin\Http\Requests\StoreDepartmentRequest;
use Hito\Admin\Http\Requests\UpdateDepartmentRequest;
use Hito\Platform\Models\Department;
use Hito\Platform\Services\DepartmentService;
use Hito\Platform\Services\UserService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class DepartmentController extends Controller
{
    private string $entitySingular = 'Department';
    private string $entityPlural = 'Departments';

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

        return AdminResourceFactory::index($departments, function (Department $department) {
            return view('hito-admin::departments._index-item', compact('department'));
        })
            ->entity($this->entitySingular, $this->entityPlural)
            ->createUrl(route('admin.departments.create'))
            ->showUrl(function (Department $department) {
                if (!auth()->user()->can('show', $department)) {
                    return null;
                }

                return route('admin.departments.show', $department->id);
            })
            ->editUrl(function (Department $department) {
                if (!auth()->user()->can('edit', $department)) {
                    return null;
                }

                return route('admin.departments.edit', $department->id);
            })
            ->deleteUrl(function (Department $department) {
                if (!auth()->user()->can('delete', $department)) {
                    return null;
                }

                return route('admin.departments.delete', $department->id);
            })
            ->build();
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

        return AdminResourceFactory::create()
            ->entity($this->entitySingular, $this->entityPlural)
            ->storeUrl(route('admin.departments.store'))
            ->view(view('hito-admin::departments._form', compact('department', 'users')))
            ->build();
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

        return AdminResourceFactory::store('admin.departments.edit', $department->id)
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }

    /**
     * Display the specified resource.
     *
     * @param Department $department
     * @return View
     */
    public function show(Department $department): View
    {
        $users = $this->userService->getAll()->map(fn($user) => [
            'value' => $user->id,
            'label' => "{$user->name} {$user->surname}"
        ])->toArray();

        return AdminResourceFactory::show()
            ->entity($this->entitySingular, $this->entityPlural)
            ->title($department->name)
            ->view(view('hito-admin::departments._show', compact('department', 'users')))
            ->editUrl(route('admin.departments.edit', $department->id))
            ->deleteUrl(route('admin.departments.delete', $department->id))
            ->indexUrl(route('admin.departments.index'))
            ->build();
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

        return AdminResourceFactory::edit()
            ->entity($this->entitySingular, $this->entityPlural)
            ->updateUrl(route('admin.departments.update', compact('department')))
            ->view(view('hito-admin::departments._form', compact('department', 'users')))
            ->build();
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

        return AdminResourceFactory::update()
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }

    /**
     * @param Department $department
     * @return View
     * @throws AuthorizationException
     */
    public function delete(Department $department): View
    {
        $this->authorize('delete', $department);

        return AdminResourceFactory::delete()
            ->entity($this->entitySingular, $this->entityPlural)
            ->isUsed(false)
            ->destroyUrl(route('admin.departments.destroy', compact('department')))
            ->cancelUrl(route('admin.departments.show', $department->id))
            ->build();
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

        return AdminResourceFactory::destroy('admin.departments.index')
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }
}
