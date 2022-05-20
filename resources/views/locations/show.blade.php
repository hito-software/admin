@extends('hito-admin::_layout')

@section('title', $location->name)

@section('actions')
    @can('update', $location)
        <a href="{{ route('admin.locations.edit', $location->id) }}"
           class="bg-blue-600 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
            <i class="fas fa-edit"></i> Edit
        </a>
    @endcan

    @can('delete', $location)
        <a href="{{ route('admin.locations.delete', $location->id) }}"
           class="bg-red-600 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
           <i class="fas fa-trash"></i> Delete
        </a>
    @endcan
@endsection

@section('content')
    <div class="space-y-4 my-4 bg-white p-4 rounded-lg shadow">
        <div>
            <label class="block">Name</label>
            <div class="border py-2 px-4 rounded w-full">{{ $location->name }}</div>
        </div>

        <div>
            <label for="form_description" class="block">Description</label>
            @if(is_null($location->description))
                 <x-hito::alert type="warn">Description was not provided</x-hito::alert>
            @else
            <textarea disabled id="form_description" cols="30" rows="2"
                      class="border py-2 px-4 rounded w-full">{{ $location->description }}</textarea>
            @endif
        </div>

        @if(!empty($location->country))
            <div>
                <label class="block">Country</label>
                <div class="border py-2 px-4 rounded w-full">{{ $location->country?->name }}</div>
            </div>
        @endif

        <div>
            <label class="block">Address</label>
            <div class="border py-2 px-4 rounded w-full">{{ $location->address }}</div>
        </div>
    </div>
@endsection
