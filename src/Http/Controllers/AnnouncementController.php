<?php

namespace Hito\Admin\Http\Controllers;

use Carbon\Carbon;
use Hito\Admin\Factories\AdminResourceFactory;
use Hito\Admin\Http\Requests\StoreAnnouncementRequest;
use Hito\Admin\Http\Requests\UpdateAnnouncementRequest;
use Hito\Platform\Models\Announcement;
use Hito\Platform\Services\AnnouncementService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    private string $entitySingular = 'Announcement';
    private string $entityPlural = 'Announcements';

    public function __construct(private readonly AnnouncementService $announcementService)
    {
        $this->authorizeResource(Announcement::class);
    }

    /**
     * @return View
     */
    public function index()
    {
        $announcements = $this->announcementService->getPaginated(orderBy: 'created_at');

        return AdminResourceFactory::index($announcements, function (Announcement $announcement) {
            return view('hito-admin::announcements._index-item', compact('announcement'));
        })
            ->entity($this->entitySingular, $this->entityPlural)
            ->createUrl(route('admin.announcements.create'))
            ->showUrl(function (Announcement $announcement) {
                if (!auth()->user()->can('show', $announcement)) {
                    return null;
                }

                return route('admin.announcements.show', $announcement->id);
            })
            ->editUrl(function (Announcement $announcement) {
                if (!auth()->user()->can('edit', $announcement)) {
                    return null;
                }

                return route('admin.announcements.edit', $announcement->id);
            })
            ->deleteUrl(function (Announcement $announcement) {
                if (!auth()->user()->can('delete', $announcement)) {
                    return null;
                }

                return route('admin.announcements.delete', $announcement->id);
            })
            ->build();
    }

    /**
     * @return View
     */
    public function create(Announcement $announcement)
    {
        $announcement->fill(['published_at' => Carbon::now()]);

        return AdminResourceFactory::create()
            ->entity($this->entitySingular, $this->entityPlural)
            ->storeUrl(route('admin.announcements.store'))
            ->view(view('hito-admin::announcements._form', compact('announcement')))
            ->build();
    }

    /**
     * @param StoreAnnouncementRequest $request
     * @return RedirectResponse
     */
    public function store(StoreAnnouncementRequest $request)
    {
        $data = $this->getDataFromRequest($request);

        $announcement = $this->announcementService->create(
            $data['name'],
            $data['description'],
            $data['content'],
            $data['published_at'],
            $data['pin_start_at'],
            $data['pin_end_at'],
            $data['locations'],
            auth()->id()
        );

        return AdminResourceFactory::store('admin.announcements.edit', $announcement->id)
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }

    /**
     * @param Announcement $announcement
     * @return View
     */
    public function show(Announcement $announcement)
    {
        return AdminResourceFactory::show()
            ->entity($this->entitySingular, $this->entityPlural)
            ->title($announcement->name)
            ->view(view('hito-admin::announcements._show', compact('announcement')))
            ->editUrl(route('admin.announcements.edit', $announcement->id))
            ->deleteUrl(route('admin.announcements.delete', $announcement->id))
            ->indexUrl(route('admin.announcements.index'))
            ->build();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Announcement $announcement
     * @return View
     */
    public function edit(Announcement $announcement)
    {
        return AdminResourceFactory::edit()
            ->entity($this->entitySingular, $this->entityPlural)
            ->updateUrl(route('admin.announcements.update', compact('announcement')))
            ->view(view('hito-admin::announcements._form', compact('announcement')))
            ->build();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAnnouncementRequest $request
     * @param Announcement $announcement
     * @return RedirectResponse
     */
    public function update(UpdateAnnouncementRequest $request, Announcement $announcement)
    {
        $data = $this->getDataFromRequest($request);

        $this->announcementService->update($announcement->id, $data);

        return AdminResourceFactory::update()
            ->entity($this->entitySingular, $this->entityPlural)
            ->build();
    }

    /**
     * @param Announcement $announcement
     * @return View
     * @throws AuthorizationException
     */
    public function delete(Announcement $announcement): View
    {
        $this->authorize('delete', $announcement);

        return AdminResourceFactory::delete()
            ->entity($this->entitySingular, $this->entityPlural)
            ->isUsed(false)
            ->destroyUrl(route('admin.announcements.destroy', compact('announcement')))
            ->cancelUrl(route('admin.announcements.show', $announcement->id))
            ->build();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Announcement $announcement
     * @return RedirectResponse
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return AdminResourceFactory::destroy('admin.announcements.index')
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
            'pin_start_at',
            'pin_end_at',
            'locations'
        ]);

        if (!empty($data['published_at'])) {
            $data['published_at'] = Carbon::parse(request('published_at'));
        }

        $data['pin_start_at'] = request('pin_start_at') ? Carbon::parse(request('pin_start_at')) : null;
        $data['pin_end_at'] = request('pin_end_at') ? Carbon::parse(request('pin_end_at')) : null;

        $data['locations'] = array_filter(request('locations', []), fn($location) => !is_null($location));

        return $data;
    }
}
