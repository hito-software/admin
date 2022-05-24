<?php

namespace Hito\Admin\Http\Controllers;

use Hito\Admin\Http\Controllers\Controller;
use Hito\Admin\Http\Requests\StoreUserRequest;
use Hito\Admin\Http\Requests\UpdateUserRequest;
use Hito\Platform\Models\Group;
use Hito\Platform\Models\User;
use Hito\Platform\Services\GroupService;
use Hito\Platform\Services\PermissionService;
use Hito\Platform\Services\UserService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function __construct(
        private UserService       $userService,
        private GroupService      $groupService,
        private PermissionService $permissionService)
    {
        $this->authorizeResource(User::class);
    }

    /**
     * @return View
     */
    public function index(): View
    {
        $users = $this->userService->getAllPaginated();

        return view('hito-admin::users.index', compact('users'));
    }

    /**
     * @return View
     */
    public function create(User $user): View
    {
        return view('hito-admin::users.create', compact('user'));
    }

    /**
     * @param StoreUserRequest $request
     * @return RedirectResponse
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->only([
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|email|unique:users',
            'location' => 'required|uuid',
            'timezone' => 'required|uuid',
            'skype' => 'nullable',
            'whatsapp' => 'nullable',
            'telegram' => 'nullable',
            'phone' => 'nullable|numeric|regex:/^[\+]?[0-9]{4,20}/',
        ]);

        $user = $this->userService->create(
            $data['name'],
            $data['surname'],
            $data['email'],
            $data['location'],
            $data['timezone'],
            $data['skype'],
            $data['whatsapp'],
            $data['telegram'],
            $data['phone']
        );

        return redirect()->route('admin.users.edit', $user->id)
            ->with('success', \Lang::get('forms.created_successfully', ['entity' => 'User']));
    }

    /**
     * @param User $user
     * @return View
     */
    public function show(User $user): View
    {
        $groups = $user->groups;
        $permissions = $user->permissions;

        return view('hito-admin::users.show', compact('user', 'groups', 'permissions'));
    }

    /**
     * @param User $user
     * @return View
     */
    public function edit(User $user): View
    {
        $groups = $this->groupService->getAll()->map(fn($group) => [
            'value' => $group->id,
            'label' => $group->name
        ])->toArray();

        $permissions = $this->permissionService->getAll()->map(fn($permission) => [
            'value' => $permission->id,
            'label' => $permission->name
        ])->toArray();

        return view('hito-admin::users.edit', compact('user', 'groups', 'permissions'));
    }

    /**
     * @param UpdateUserRequest $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = request()->only(['name', 'surname', 'email', 'phone', 'skype', 'whatsapp', 'telegram']);
        $data['groups'] = request('groups', []);
        $data['permissions'] = request('permissions', []);
        $data['location_id'] = request('location');
        $data['timezone_id'] = request('timezone');

        $this->userService->update($user->id, $data);
        return back()->with('success', \Lang::get('forms.updated_successfully', ['entity' => 'User']));
    }

    /**
     * @param User $user
     * @return View
     * @throws AuthorizationException
     */
    public function delete(User $user): View
    {
        $this->authorize('delete', $user);

        return view('shared.delete-entity', [
            'action' => route('admin.users.destroy', $user->id),
            'noAction' => route('admin.users.show', $user->id),
            'entity' => 'user'
        ]);
    }

    /**
     * @param User $user
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', \Lang::get('forms.deleted_successfully', ['entity' => 'User']));
    }

    /**
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        Password::sendResetLink(['email' => $user->email]);

        return back()->with('success', "A reset link was sent to the user's email address.");
    }
}
