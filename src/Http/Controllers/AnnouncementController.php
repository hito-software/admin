<?php

namespace Hito\Admin\Http\Controllers;

use Hito\Admin\Http\Controllers\Controller;
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
    public function create()
    {
        return view('hito-admin::announcements.create', ['announcement' => new Announcement()]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $data = $this->getValidatedData($request);
        $data['locations'] = array_filter($data['locations'], fn($location) => !is_null($location));

        $announcement = $this->announcementService->create($data['name'], $data['description'], $data['content'],
            $data['published_at'] ?? null, $data['pin_start_at'] ?? null,
            $data['pin_end_at'] ?? null, $data['locations'] ?? null, auth()->id());

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
     * @param Request $request
     * @param Announcement $announcement
     * @return RedirectResponse
     */
    public function update(Request $request, Announcement $announcement)
    {
        $data = $this->getValidatedData($request);
        $locations = array_filter($data['locations'], fn($location) => !is_null($location));
        unset($data['locations']);

        $this->announcementService->update($announcement->id, $data, $locations);

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

    private function getValidatedData(Request $request): array
    {
        $data = $this->validate($request, [
            'name' => 'required',
            'description' => 'required|max:255',
            'content' => 'required',
            'published_at' => 'required|date_format:Y-m-d H:i',
            'pin_start_at' => 'nullable|date_format:Y-m-d H:i|after_or_equal:published_at|required_unless:pin_end_at,null',
            'pin_end_at' => 'nullable|date_format:Y-m-d H:i|after:pin_start_at',
            'locations' => 'nullable|array',
            'location.*' => 'uuid'
        ]);

        if (!empty($data['published_at'])) {
            $data['published_at'] = Carbon::parse($data['published_at']);
        }

        if (!empty($data['pin_start_at'])) {
            $data['pin_start_at'] = Carbon::parse($data['pin_start_at']);
        }

        if (!empty($data['pin_end_at'])) {
            $data['pin_end_at'] = Carbon::parse($data['pin_end_at']);
        }

        return $data;
    }
}
