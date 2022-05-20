@extends('hito-admin::_layout')

@section('title', $procedure->name)

@section('actions')
    @can('update', $procedure)
        <a href="{{ route('admin.procedures.edit', $procedure->id) }}"
           class="bg-blue-600 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
            <i class="fas fa-edit"></i> Edit
        </a>
    @endcan

    @can('delete', $procedure)
        <a href="{{ route('admin.procedures.delete', $procedure->id) }}"
           class="bg-red-600 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
           <i class="fas fa-trash"></i> Delete
        </a>
    @endcan
@endsection

@section('content')
    <div class="space-y-4 my-4 bg-white p-4 rounded-lg shadow">
        <div>
            <label for="form_name" class="block">Name</label>
            <div class="border py-2 px-4 rounded w-full">{{ $procedure->name }}</div>
        </div>

        @if(!is_null($procedure->status))
            <div>
                <label for="form_name" class="block">Status</label>
                <div class="border py-2 px-4 rounded w-full">{{ $procedure->status }}</div>
            </div>
        @endif

        <div>
            <label for="form_description" class="block">Description</label>
            <textarea id="form_description" cols="30" rows="2" class="border py-2 px-4 rounded w-full"
                      disabled>{{ $procedure->description }}</textarea>
        </div>

        <div>
            <label for="form_description" class="block">Content</label>
            <div class="border py-2 px-4 rounded w-full">{!! $procedure->content !!}</div>
        </div>

        <div>
            <label for="form_description" class="block">Locations</label>
            <div class="border py-2 px-4 rounded w-full">
                <div class="flex flex-wrap gap-2">
                    @forelse($procedure->locations as $location)
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
