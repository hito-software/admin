<?php

namespace Hito\Admin\Http\Controllers;

use Hito\Admin\Http\Controllers\Controller;
use Hito\Platform\Models\Location;
use Hito\Platform\Services\CountryService;
use Hito\Platform\Services\LocationService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class LocationController extends Controller
{
    public function __construct(private LocationService $locationService,
                                private CountryService  $countryService)
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

        return view('hito-admin::locations.index', compact('locations'));
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

        return view('hito-admin::locations.create', compact('location', 'countries'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request, Location $location): RedirectResponse
    {
        $this->validate($request, [
            'name' => [
                'required',
                'max:100',
                Rule::unique('locations')->ignoreModel($location)->withoutTrashed()
            ],
            'description' => 'nullable|max:255',
            'country' => 'required|uuid',
            'address' => 'required|max:150',
        ]);

        $location = $this->locationService->create(request('name'),
            request('country'), request('address'), request('description'), auth()->user()->id);

        return redirect()->route('admin.locations.edit', $location->id)
            ->with('success', \Lang::get('forms.created_successfully', ['entity' => 'Location']));
    }

    /**
     * Display the specified resource.
     *
     * @param Location $location
     * @return View
     */
    public function show(Location $location): View
    {
        $users = $location->users;

        return view('hito-admin::locations.show', compact('location', 'users'));
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

        return view('hito-admin::locations.edit', compact('location', 'countries'));
    }

    /**
     * @param Request $request
     * @param Location $location
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, Location $location): RedirectResponse
    {
        $this->validate($request, [
            'name' => [
                'required',
                'max:100',
                Rule::unique('locations')->ignoreModel($location)->withoutTrashed()
            ],
            'description' => 'nullable|max:255',
            'country' => 'required|uuid',
            'address' => 'required|max:150',
        ]);

        $data = request(['name', 'description', 'address']);
        $data['country_id'] = request('country');

        $this->locationService->update($location->id, $data);

        return redirect()->back()->with('success', \Lang::get('forms.updated_successfully', ['entity' => 'Location']));
    }

    /**
     * @param Location $location
     * @return View
     * @throws AuthorizationException
     */
    public function delete(Location $location): View
    {
        $this->authorize('delete', $location);

        return view('shared.delete-entity', [
            'action' => route('admin.locations.destroy', $location->id),
            'noAction' => route('admin.locations.show', $location->id),
            'entity' => 'location'
        ]);
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

        return redirect()->route('admin.locations.index')
            ->with('success', \Lang::get('forms.deleted_successfully', ['entity' => 'Location']));
    }
}
