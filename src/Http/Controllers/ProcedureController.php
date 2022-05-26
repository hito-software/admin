<?php

namespace Hito\Admin\Http\Controllers;

use Carbon\Carbon;
use Hito\Admin\Enums\Status;
use Hito\Admin\Factories\AdminResourceFactory;
use Hito\Admin\Http\Requests\StoreProcedureRequest;
use Hito\Admin\Http\Requests\UpdateProcedureRequest;
use Hito\Platform\Models\Procedure;
use Hito\Platform\Services\ProcedureService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProcedureController extends Controller
{
    private string $entitySingular = 'Procedure';
    private string $entityPlural = 'Procedures';

    public function __construct(private readonly ProcedureService $procedureService)
    {
        $this->authorizeResource(Procedure::class);
    }

    /**
     * @return View
     */
    public function index()
    {
        $procedures = $this->procedureService->getPaginated();

        return AdminResourceFactory::index($procedures, function (Procedure $procedure) {
            return view('hito-admin::procedures._index-item', compact('procedure'));
        })
            ->entity($this->entitySingular, $this->entityPlural)
            ->createUrl(route('admin.procedures.create'))
            ->showUrl(function (Procedure $procedure) {
                if (!auth()->user()->can('show', $procedure)) {
                    return null;
                }

                return route('admin.procedures.show', $procedure->id);
            })
            ->editUrl(function (Procedure $procedure) {
                if (!auth()->user()->can('edit', $procedure)) {
                    return null;
                }

                return route('admin.procedures.edit', $procedure->id);
            })
            ->deleteUrl(function (Procedure $procedure) {
                if (!auth()->user()->can('delete', $procedure)) {
                    return null;
                }

                return route('admin.procedures.delete', $procedure->id);
            })
            ->build();
    }

    /**
     * @return View
     */
    public function create(Procedure $procedure)
    {
        return AdminResourceFactory::create()
            ->entity($this->entitySingular, $this->entityPlural)
            ->storeUrl(route('admin.procedures.store'))
            ->view(view('hito-admin::procedures._form', compact('procedure')))
            ->build();
    }

    /**
     * @param StoreProcedureRequest $request
     * @return RedirectResponse
     */
    public function store(StoreProcedureRequest $request)
    {
        $data = $this->getDataFromRequest($request);

        $procedure = $this->procedureService->create(
            $data['name'],
            $data['description'],
            $data['content'],
            $data['status'],
            $data['published_at'] ?? null,
            $data['locations'] ?? null
        );

        return AdminResourceFactory::store('admin.procedures.edit', $procedure->id)
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }

    /**
     * @param Procedure $procedure
     * @return View
     */
    public function show(Procedure $procedure)
    {
        return AdminResourceFactory::show()
            ->entity($this->entitySingular, $this->entityPlural)
            ->title($procedure->name)
            ->view(view('hito-admin::procedures._show', compact('procedure')))
            ->editUrl(route('admin.procedures.edit', $procedure->id))
            ->deleteUrl(route('admin.procedures.delete', $procedure->id))
            ->indexUrl(route('admin.procedures.index'))
            ->build();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Procedure $procedure
     * @return View
     */
    public function edit(Procedure $procedure)
    {
        return AdminResourceFactory::edit()
            ->entity($this->entitySingular, $this->entityPlural)
            ->updateUrl(route('admin.procedures.update', compact('procedure')))
            ->view(view('hito-admin::procedures._form', compact('procedure')))
            ->build();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProcedureRequest $request
     * @param Procedure $procedure
     * @return RedirectResponse
     */
    public function update(UpdateProcedureRequest $request, Procedure $procedure)
    {
        $data = $this->getDataFromRequest($request);

        $this->procedureService->update($procedure->id, $data);

        return AdminResourceFactory::update()
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }

    /**
     * @param Procedure $procedure
     * @return View
     * @throws AuthorizationException
     */
    public function delete(Procedure $procedure): View
    {
        $this->authorize('delete', $procedure);

        return AdminResourceFactory::delete()
            ->entity($this->entitySingular, $this->entityPlural)
            ->isUsed(false)
            ->destroyUrl(route('admin.procedures.destroy', compact('procedure')))
            ->cancelUrl(route('admin.procedures.show', $procedure->id))
            ->build();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Procedure $procedure
     * @return RedirectResponse
     */
    public function destroy(Procedure $procedure)
    {
        $procedure->delete();

        return AdminResourceFactory::destroy('admin.procedures.index')
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }

    private function getDataFromRequest(Request $request): array
    {
        $data = $request->only([
            'name',
            'description',
            'content',
            'published_at',
            'status',
            'locations'
        ]);

        if (!empty($data['published_at'])) {
            $data['published_at'] = Carbon::parse($data['published_at']);
        }

        $data['locations'] = array_filter(request('locations', []), fn($location) => !is_null($location));

        return $data;
    }
}
