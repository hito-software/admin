@extends('hito-admin::_layout')

@section('title', $announcement->name)

@section('actions')
    @can('update', $announcement)
        <a href="{{ route('admin.announcements.edit', $announcement->id) }}"
           class="bg-blue-600 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
            <i class="fas fa-edit"></i> Edit
        </a>
    @endcan

    @can('delete', $announcement)
        <a href="{{ route('admin.announcements.delete', $announcement->id) }}"
           class="bg-red-600 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
           <i class="fas fa-trash"></i> Delete
        </a>
    @endcan
@endsection

@section('content')
    <div class="space-y-4 my-4 bg-white p-4 rounded-lg shadow">
        <div>
            <label for="form_name" class="block">Name</label>
            <div class="border py-2 px-4 rounded w-full">{{ $announcement->name }}</div>
        </div>

        <div>
            <label for="form_name" class="block">Publish date</label>
            <div class="border py-2 px-4 rounded w-full">{{ $announcement->published_at }}</div>
        </div>

        @if(!is_null($announcement->pin_start_at))
        <div>
            <label for="form_name" class="block">Pin start at</label>
            <div class="border py-2 px-4 rounded w-full">{{ $announcement->pin_start_at }}</div>
        </div>
        @endif

        @if(!is_null($announcement->pin_end_at))
        <div>
            <label for="form_name" class="block">Pin end at</label>
            <div class="border py-2 px-4 rounded w-full">{{ $announcement->pin_end_at }}</div>
        </div>
        @endif

        <div>
            <label for="form_description" class="block">Description</label>
            <textarea id="form_description" cols="30" rows="2" class="border py-2 px-4 rounded w-full"
                      disabled>{{ $announcement->description }}</textarea>
        </div>

        <div>
            <label for="form_description" class="block">Content</label>
            <div class="border py-2 px-4 rounded w-full">{!! $announcement->content !!}</div>
        </div>

        <div>
            <label for="form_description" class="block">Locations</label>
            <div class="border py-2 px-4 rounded w-full">
                <div class="flex flex-wrap gap-2">
                    @forelse($announcement->locations as $location)
                        <a class="py-1 px-2 bg-blue-500 rounded text-sm uppercase font-bold text-white"
                           href="{{ route('admin.locations.show', $location->id) }}">{{ $location->name }}</a>
                    @empty
                        <span class="py-1 px-2 bg-green-500 rounded text-sm uppercase font-bold text-white">All locations</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
