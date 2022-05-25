<?php

namespace Hito\Admin\Http\Controllers;

use Hito\Admin\Factories\AdminResourceFactory;
use Hito\Admin\Http\Requests\StoreLocationRequest;
use Hito\Admin\Http\Requests\UpdateLocationRequest;
use Hito\Platform\Models\Location;
use Hito\Platform\Services\CountryService;
use Hito\Platform\Services\LocationService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class LocationController extends Controller
{
    private string $entitySingular = 'Location';
    private string $entityPlural = 'Locations';

    public function __construct(
        private readonly LocationService $locationService,
        private readonly CountryService  $countryService)
    {
        $this->authorizeResource(Location::class);;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $locations = $this->locationService->getAllPaginated();

        return AdminResourceFactory::index($locations, function (Location $location) {
            return view('hito-admin::locations._index-item', compact('location'));
        })
            ->entity($this->entitySingular, $this->entityPlural)
            ->createUrl(route('admin.locations.create'))
            ->showUrl(function (Location $location) {
                if (!auth()->user()->can('show', $location)) {
                    return null;
                }

                return route('admin.locations.show', $location->id);
            })
            ->editUrl(function (Location $location) {
                if (!auth()->user()->can('edit', $location)) {
                    return null;
                }

                return route('admin.locations.edit', $location->id);
            })
            ->deleteUrl(function (Location $location) {
                if (!auth()->user()->can('delete', $location)) {
                    return null;
                }

                return route('admin.locations.delete', $location->id);
            })
            ->build();
    }

    /**
     * @param Location $location
     * @return View
     */
    public function create(Location $location): View
    {
        $countries = $this->countryService->getAll()->map(fn($country) => [
            'value' => $country->id,
            'label' => $country->name
        ])->toArray();

        return AdminResourceFactory::create()
            ->entity($this->entitySingular, $this->entityPlural)
            ->storeUrl(route('admin.locations.store'))
            ->view(view('hito-admin::locations._form', compact('location','countries')))
            ->build();
    }

    /**
     * @param StoreLocationRequest $request
     * @param Location $location
     * @return RedirectResponse
     */
    public function store(StoreLocationRequest $request, Location $location): RedirectResponse
    {
        $location = $this->locationService->create(request('name'),
            request('country'), request('address'), request('description'));

        return AdminResourceFactory::store('admin.locations.edit', $location->id)
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }

    /**
     * Display the specified resource.
     *
     * @param Location $location
     * @return View
     */
    public function show(Location $location): View
    {
        return AdminResourceFactory::show()
            ->entity($this->entitySingular, $this->entityPlural)
            ->title($location->name)
            ->view(view('hito-admin::locations._show', compact('location')))
            ->editUrl(route('admin.locations.edit', $location->id))
            ->deleteUrl(route('admin.locations.delete', $location->id))
            ->indexUrl(route('admin.locations.index'))
            ->build();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Location $location
     * @return View
     */
    public function edit(Location $location): View
    {
        $countries = $this->countryService->getAll()->map(fn($country) => [
            'value' => $country->id,
            'label' => $country->name
        ])->toArray();

        return AdminResourceFactory::edit()
            ->entity($this->entitySingular, $this->entityPlural)
            ->updateUrl(route('admin.locations.update', compact('location')))
            ->view(view('hito-admin::locations._form', compact('location','countries')))
            ->build();
    }

    /**
     * @param UpdateLocationRequest $request
     * @param Location $location
     * @return RedirectResponse
     */
    public function update(UpdateLocationRequest $request, Location $location): RedirectResponse
    {
        $data = request(['name', 'description', 'address']);
        $data['country_id'] = request('country');

        $this->locationService->update($location->id, $data);

        return AdminResourceFactory::update()
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }

    /**
     * @param Location $location
     * @return View
     * @throws AuthorizationException
     */
    public function delete(Location $location): View
    {
        $this->authorize('delete', $location);

        return AdminResourceFactory::delete()
            ->entity($this->entitySingular, $this->entityPlural)
            ->isUsed(false)
            ->destroyUrl(route('admin.locations.destroy', compact('location')))
            ->cancelUrl(route('admin.locations.show', $location->id))
            ->build();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Location $location
     * @return RedirectResponse
     */
    public function destroy(Location $location): RedirectResponse
    {
        $location->delete();

        return AdminResourceFactory::destroy('admin.locations.index')
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }
}
