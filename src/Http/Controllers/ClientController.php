<?php

namespace Hito\Admin\Http\Controllers;

use Hito\Admin\Factories\AdminResourceFactory;
use Hito\Admin\Http\Requests\StoreClientRequest;
use Hito\Admin\Http\Requests\UpdateClientRequest;
use Hito\Platform\Models\Client;
use Hito\Platform\Services\ClientService;
use Hito\Platform\Services\CountryService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ClientController extends Controller
{
    private string $entitySingular = 'Client';
    private string $entityPlural = 'Clients';

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

        return AdminResourceFactory::index($clients, function (Client $client) {
            return view('hito-admin::clients._index-item', compact('client'));
        })
            ->entity($this->entitySingular, $this->entityPlural)
            ->createUrl(route('admin.clients.create'))
            ->showUrl(function (Client $client) {
                if (!auth()->user()->can('show', $client)) {
                    return null;
                }

                return route('admin.clients.show', $client->id);
            })
            ->editUrl(function (Client $client) {
                if (!auth()->user()->can('edit', $client)) {
                    return null;
                }

                return route('admin.clients.edit', $client->id);
            })
            ->deleteUrl(function (Client $client) {
                if (!auth()->user()->can('delete', $client)) {
                    return null;
                }

                return route('admin.clients.delete', $client->id);
            })
            ->build();
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

        return AdminResourceFactory::create()
            ->entity($this->entitySingular, $this->entityPlural)
            ->storeUrl(route('admin.clients.store'))
            ->view(view('hito-admin::clients._form', compact('client', 'countries')))
            ->build();
    }

    /**
     * @param StoreClientRequest $request
     * @return RedirectResponse
     */
    public function store(StoreClientRequest $request)
    {
        $client = $this->clientService->create(
            request('name'),
            request('description'),
            request('country'),
            request('address')
        );

        return AdminResourceFactory::store('admin.clients.edit', $client->id)
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }

    /**
     * @param Client $client
     * @return View
     */
    public function show(Client $client)
    {
        $countries = $this->countryService->getAll()->map(fn($country) => [
            'value' => $country->id,
            'label' => $country->name
        ])->toArray();

        return AdminResourceFactory::show()
            ->entity($this->entitySingular, $this->entityPlural)
            ->title($client->name)
            ->view(view('hito-admin::clients._show', compact('client', 'countries')))
            ->editUrl(route('admin.clients.edit', $client->id))
            ->deleteUrl(route('admin.clients.delete', $client->id))
            ->indexUrl(route('admin.clients.index'))
            ->build();
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

        return AdminResourceFactory::edit()
            ->entity($this->entitySingular, $this->entityPlural)
            ->updateUrl(route('admin.clients.update', compact('client')))
            ->view(view('hito-admin::clients._form', compact('client', 'countries')))
            ->build();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateClientRequest $request
     * @param Client $client
     * @return RedirectResponse
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        $data = request(['name', 'description', 'address']);
        $data['country_id'] = request('country');

        $this->clientService->update($client->id, $data);

        return AdminResourceFactory::update()
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }

    /**
     * @param Client $client
     * @return View
     * @throws AuthorizationException
     */
    public function delete(Client $client)
    {
        $this->authorize('delete', $client);

        return AdminResourceFactory::delete()
            ->entity($this->entitySingular, $this->entityPlural)
            ->isUsed(false)
            ->destroyUrl(route('admin.clients.destroy', compact('client')))
            ->cancelUrl(route('admin.clients.show', $client->id))
            ->build();
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

        return AdminResourceFactory::destroy('admin.clients.index')
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }
}
