<?php

namespace Hito\Admin\Http\Controllers;

use Hito\Admin\Http\Controllers\Controller;
use Hito\Admin\Http\Requests\StoreClientRequest;
use Hito\Admin\Http\Requests\UpdateClientRequest;
use Hito\Platform\Models\Client;
use Hito\Platform\Services\ClientService;
use Hito\Platform\Services\CountryService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ClientController extends Controller
{
    public function __construct(
        private readonly ClientService  $clientService,
        private readonly CountryService $countryService)
    {
        $this->authorizeResource(Client::class);
    }

    /**
     * @return View
     */
    public function index()
    {
        $clients = $this->clientService->getAllPaginated();

        return view('hito-admin::clients.index', compact('clients'));
    }

    /**
     * @param Client $client
     * @return View
     */
    public function create(Client $client)
    {
        $countries = $this->countryService->getAll()->map(fn($country) => [
            'value' => $country->id,
            'label' => $country->name
        ])->toArray();

        return view('hito-admin::clients.create', compact('client', 'countries'));
    }

    /**
     * @param StoreClientRequest $request
     * @param Client $client
     * @return RedirectResponse
     */
    public function store(StoreClientRequest $request, Client $client)
    {
        $request = $this->clientService->create(
            request('name'),
            request('description'),
            request('country'),
            request('address')
        );

        return redirect()->route('admin.clients.edit', $request->id)
            ->with('success', \Lang::get('forms.created_successfully', ['entity' => 'Client']));
    }

    /**
     * @param Client $client
     * @return View
     */
    public function show(Client $client)
    {
        $country = $client->country;

        return view('hito-admin::clients.show', compact('client', 'country'));
    }

    /**
     * @param Client $client
     * @return View
     */
    public function edit(Client $client)
    {
        $countries = $this->countryService->getAll()->map(fn($country) => [
            'value' => $country->id,
            'label' => $country->name
        ])->toArray();

        return view('hito-admin::clients.edit', compact('client', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Client $client
     * @return RedirectResponse
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        $data = request(['name', 'description', 'address']);
        $data['country_id'] = request('country');

        $this->clientService->update($client->id, $data);
        return back()->with('success', \Lang::get('forms.updated_successfully', ['entity' => 'Client']));
    }

    /**
     * @param Client $client
     * @return View
     * @throws AuthorizationException
     */
    public function delete(Client $client)
    {
        $this->authorize('delete', $client);

        return view('shared.delete-entity', [
            'action' => route('admin.clients.destroy', $client->id),
            'noAction' => route('admin.clients.show', $client->id),
            'entity' => 'client'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Client $client
     * @return RedirectResponse
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('admin.clients.index')
            ->with('success', \Lang::get('forms.deleted_successfully', ['entity' => 'Client']));
    }
}
