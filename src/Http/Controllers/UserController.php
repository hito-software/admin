<?php

namespace Hito\Admin\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Hito\Admin\Factories\AdminResourceFactory;
use Hito\Admin\Http\Requests\StoreUserRequest;
use Hito\Admin\Http\Requests\UpdateUserRequest;
use Hito\Platform\Models\User;
use Hito\Platform\Services\GroupService;
use Hito\Platform\Services\PermissionService;
use Hito\Platform\Services\UserService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    private string $entitySingular = 'User';
    private string $entityPlural = 'Users';

    public function __construct(
        private readonly UserService       $userService,
        private readonly GroupService      $groupService,
        private readonly PermissionService $permissionService)
    {
        $this->authorizeResource(User::class);
    }

    /**
     * @return View
     */
    public function index(): View
    {
        $users = $this->userService->getAllPaginated();

        return AdminResourceFactory::index($users, function (User $user) {
            return view('hito-admin::users._index-item', compact('user'));
        })
            ->entity($this->entitySingular, $this->entityPlural)
            ->createUrl(route('admin.users.create'))
            ->showUrl(function (User $user) {
                if (!auth()->user()->can('show', $user)) {
                    return null;
                }

                return route('admin.users.show', $user->id);
            })
            ->editUrl(function (User $user) {
                if (!auth()->user()->can('edit', $user)) {
                    return null;
                }

                return route('admin.users.edit', $user->id);
            })
            ->deleteUrl(function (User $user) {
                if (!auth()->user()->can('delete', $user)) {
                    return null;
                }

                return route('admin.users.delete', $user->id);
            })
            ->build();
    }

    /**
     * @param User $user
     * @return View
     */
    public function create(User $user): View
    {
        return AdminResourceFactory::create()
            ->entity($this->entitySingular, $this->entityPlural)
            ->storeUrl(route('admin.users.store'))
            ->view(view('hito-admin::users._form', compact('user')))
            ->build();
    }

    /**
     * @param StoreUserRequest $request
     * @return RedirectResponse
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->only([
            'name',
            'surname',
            'email',
            'birthdate',
            'location',
            'timezone',
            'skype',
            'whatsapp',
            'telegram',
            'phone',
        ]);

        if ($birthdate = \Arr::get($data, $data['birthdate'])) {
            $birthdate = Carbon::parse($birthdate);
        }

        $user = $this->userService->create(
            $data['name'],
            $data['surname'],
            $data['email'],
            $data['location'],
            $data['timezone'],
            $birthdate,
            $data['phone'],
            $data['skype'],
            $data['whatsapp'],
            $data['telegram'],
        );

        return AdminResourceFactory::store('admin.users.edit', $user->id)
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }

    /**
     * @param User $user
     * @return View
     */
    public function show(User $user): View
    {
        $groups = $this->groupService->getAll()->map(fn($group) => [
            'value' => $group->id,
            'label' => $group->name
        ])->toArray();

        $permissions = $this->permissionService->getAll()->map(fn($permission) => [
            'value' => $permission->id,
            'label' => $permission->name
        ])->toArray();

        return AdminResourceFactory::show()
            ->entity($this->entitySingular, $this->entityPlural)
            ->title($user->name)
            ->view(view('hito-admin::users._show', compact('user', 'groups', 'permissions')))
            ->editUrl(route('admin.users.edit', $user->id))
            ->deleteUrl(route('admin.users.delete', $user->id))
            ->indexUrl(route('admin.users.index'))
            ->build();
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

        return AdminResourceFactory::edit()
            ->entity($this->entitySingular, $this->entityPlural)
            ->updateUrl(route('admin.users.update', compact('user')))
            ->view(view('hito-admin::users._form', compact('user', 'groups', 'permissions')))
            ->build();
    }

    /**
     * @param UpdateUserRequest $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = request()->only(['name', 'surname', 'email', 'phone', 'skype', 'whatsapp', 'telegram', 'birthdate']);
        $data['groups'] = request('groups', []);
        $data['permissions'] = request('permissions', []);
        $data['location_id'] = request('location');
        $data['timezone_id'] = request('timezone');

        $this->userService->update($user->id, $data);

        return AdminResourceFactory::update()
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }

    /**
     * @param User $user
     * @return View
     * @throws AuthorizationException
     */
    public function delete(User $user): View
    {
        $this->authorize('delete', $user);

        return AdminResourceFactory::delete()
            ->entity($this->entitySingular, $this->entityPlural)
            ->isUsed(false)
            ->destroyUrl(route('admin.users.destroy', compact('user')))
            ->cancelUrl(route('admin.users.show', $user->id))
            ->build();
    }

    /**
     * @param User $user
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return AdminResourceFactory::destroy('admin.users.index')
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
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
