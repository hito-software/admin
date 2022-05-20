<?php

namespace Hito\Admin\Http\Controllers;

use Hito\Core\Module\Services\ModuleService;
use Hito\Platform\Services\ComposerService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Nwidart\Modules\Facades\Module;
use function back;
use function config;
use function redirect;
use function request;
use function view;

class ModuleController extends Controller
{
    public function __construct(private ComposerService $composerService,
    private ModuleService $moduleService)
    {
    }

    /**
     * @return View
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('viewAny', \Nwidart\Modules\Module::class);

        $modules = $this->moduleService->getAllInstances();

        return view('hito-admin::modules.index', compact('modules'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function toggle(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'module' => 'required'
        ]);

        $id = request('module');
        $module = $this->moduleService->getById($id);

        $this->authorize('update', $module);

        if ($module->isEnabled()) {
            $this->moduleService->disable($id);
        } else {
            $this->moduleService->enable($id);
        }

        return back();
    }

    /**
     * @return View
     * @throws AuthorizationException
     */
    public function available(): View
    {
        $this->authorize('viewAny', \Nwidart\Modules\Module::class);

        $repositories = $this->composerService->getPackageList();

        return view('hito-admin::modules.available', compact('repositories'));
    }

    public function action(): RedirectResponse
    {
        $this->checkModule();

        if (request('action') === 'uninstall') {
            return $this->uninstall();
        }

        if (in_array(request('action'), ['install', 'update'])) {
            return $this->install();
        }

        return redirect()->back();
    }

    /**
     * @return RedirectResponse
     */
    private function install(): RedirectResponse
    {
        $status = $this->composerService->installPackage(request('module'), request('module_version'));

        if (is_null($status)) {
            return redirect()->back()->withErrors([
                'installation' => 'The module installation has failed for unknown reasons'
            ]);
        }

        return redirect()->route('admin.modules.available')->with('installed', $status);
    }

    /**
     * @return RedirectResponse
     */
    private function uninstall(): RedirectResponse
    {
        $status = $this->composerService->uninstallPackage(request('module'));

        if (is_null($status)) {
            return redirect()->back()->withErrors([
                'installation' => 'The module removal has failed for unknown reasons'
            ]);
        }

        return redirect()->route('admin.modules.available')->with('uninstalled', $status);
    }

    /**
     * @return RedirectResponse|bool
     * @throws ValidationException
     */
    private function checkModule(): RedirectResponse|bool
    {
        $this->validate(request(), [
            'module' => 'required'
        ]);

        $module = request('module');
        $modules = config('modules.available', []);
        $isAvailable = !!count(array_filter($modules, fn($item) => $module === $item['id']));

        if (!$isAvailable) {
            return redirect()->back()->withErrors([
                'installation' => 'Module not found'
            ]);
        }

        return true;
    }
}
