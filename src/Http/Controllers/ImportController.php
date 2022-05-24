<?php

namespace Hito\Admin\Http\Controllers;

use Hito\Admin\Http\Controllers\Controller;
use Hito\Platform\Services\ImportService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use function back;
use function view;

class ImportController extends Controller
{
    public function __construct(private readonly ImportService $importService)
    {
    }

    /**
     * @return View
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('viewAny', 'imports');

        return view('hito-admin::import.index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validate($request, [
            'users_file' => 'filled|file|mimes:csv',
            'groups_file' => 'filled|file|mimes:csv',
            'departments_file' => 'filled|file|mimes:csv',
            'clients_file' => 'filled|file|mimes:csv',
            'projects_file' => 'filled|file|mimes:csv',
            'teams_file' => 'filled|file|mimes:csv',
            'roles_file' => 'filled|file|mimes:csv',
        ]);

        $files = array_map(function ($item) {
            return $item->getRealPath();
        }, $data);

        $errors = $this->importService->import($files);

        if (!empty($errors)) {
            return back()->withErrors($errors);
        }

        return back();
    }
}
