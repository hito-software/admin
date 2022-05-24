<?php

namespace Hito\Admin\Http\Controllers;

use Carbon\Carbon;
use Hito\Admin\Enums\Status;
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
    public function __construct(private readonly ProcedureService $procedureService)
    {
        $this->authorizeResource(Procedure::class);
    }

    /**
     * @return View
     */
    public function index(Procedure $procedure)
    {
        $canEdit = auth()->user()->can('update', $procedure);
        $canCreate = auth()->user()->can('create', $procedure);

        $status = 'PUBLISHED';

        if ($canEdit || $canCreate) {
            $status = null;
        }

        $procedures = $this->procedureService->getPaginated($status);

        return view('hito-admin::procedures.index', compact('procedures'));
    }

    /**
     * @return View
     */
    public function create(Procedure $procedure)
    {
        return view('hito-admin::procedures.create', compact('procedure'));
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

        return redirect()->route('admin.procedures.edit', $procedure->id)
            ->with('success', \Lang::get('forms.created_successfully', ['entity' => 'Procedure']));
    }

    /**
     * @param Procedure $procedure
     * @return View
     */
    public function show(Procedure $procedure)
    {
        return view('hito-admin::procedures.show', compact('procedure'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Procedure $procedure
     * @return View
     */
    public function edit(Procedure $procedure)
    {
        return view('hito-admin::procedures.edit', compact('procedure'));
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

        return back()->with('success', \Lang::get('forms.updated_successfully', ['entity' => 'Procedure']));
    }

    /**
     * @param Procedure $procedure
     * @return View
     * @throws AuthorizationException
     */
    public function delete(Procedure $procedure): View
    {
        $this->authorize('delete', $procedure);

        return view('hito::_shared.delete-entity', [
            'action' => route('admin.procedures.destroy', $procedure->id),
            'noAction' => route('admin.procedures.show', $procedure->id),
            'entity' => 'procedure'
        ]);
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

        return redirect()->route('admin.procedures.index')
            ->with('success', \Lang::get('forms.deleted_successfully', ['entity' => 'Procedure']));
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
