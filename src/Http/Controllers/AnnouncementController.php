<?php

namespace Hito\Admin\Http\Controllers;

use Hito\Admin\Http\Controllers\Controller;
use Hito\Admin\Http\Requests\StoreAnnouncementRequest;
use Hito\Admin\Http\Requests\UpdateAnnouncementRequest;
use Hito\Platform\Models\Announcement;
use Hito\Platform\Services\AnnouncementService;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AnnouncementController extends Controller
{
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

        return view('hito-admin::announcements.index', compact('announcements'));
    }

    /**
     * @return View
     */
    public function create(Announcement $announcement)
    {
        $announcement->fill(['published_at' => Carbon::now()]);

        return view('hito-admin::announcements.create', compact('announcement'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
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

        return redirect()->route('admin.announcements.edit', $announcement->id)
            ->with('success', \Lang::get('forms.created_successfully', ['entity' => 'Announcement']));
    }

    /**
     * @param Announcement $announcement
     * @return View
     */
    public function show(Announcement $announcement)
    {
        return view('hito-admin::announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Announcement $announcement
     * @return View
     */
    public function edit(Announcement $announcement)
    {
        return view('hito-admin::announcements.edit', compact('announcement'));
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

        return back()->with('success', \Lang::get('forms.updated_successfully', ['entity' => 'Announcement']));
    }

    /**
     * @param Announcement $announcement
     * @return View
     * @throws AuthorizationException
     */
    public function delete(Announcement $announcement): View
    {
        $this->authorize('delete', $announcement);

        return view('shared.delete-entity', [
            'action' => route('admin.announcements.destroy', $announcement->id),
            'noAction' => route('admin.announcements.show', $announcement->id),
            'entity' => 'announcement'
        ]);
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

        return redirect()->route('admin.announcements.index')
            ->with('success', \Lang::get('forms.deleted_successfully', ['entity' => 'Announcement']));
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

        if (!empty($data['pin_start_at'])) {
            $data['pin_start_at'] = Carbon::parse(request('pin_start_at'));
        }

        if (!empty($data['pin_end_at'])) {
            $data['pin_end_at'] = Carbon::parse(request('pin_end_at'));
        }

        $data['locations'] = array_filter(request('locations', []), fn($location) => !is_null($location));

        return $data;
    }
}
