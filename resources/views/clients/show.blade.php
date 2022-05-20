@extends('hito-admin::_layout')

@section('title', $client->name)

@section('actions')
    @can('update', $client)
        <a href="{{ route('admin.clients.edit', $client->id) }}"
           class="bg-blue-600 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
            <i class="fas fa-edit"></i> Edit
        </a>
    @endcan

    @can('delete', $client)
        <a href="{{ route('admin.clients.delete', $client->id) }}"
           class="bg-red-600 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
           <i class="fas fa-trash"></i> Delete
        </a>
    @endcan
@endsection

@section('content')
    <div class="space-y-4 my-4 bg-white p-4 rounded-lg shadow">
        <div>
            <label for="form_name" class="block">Name</label>
            <div class="border py-2 px-4 rounded w-full">{{ $client->name }}</div>
        </div>

        <div>
            <label for="form_description" class="block">Description</label>
            @if(is_null($client->description))
                 <x-hito::alert type="warn">Description was not provided</x-hito::alert>
            @else
                 <textarea id="form_description" cols="30" rows="2" class="border py-2 px-4 rounded w-full" disabled>{{ $client->description }}</textarea>
            @endif
        </div>

        <div>
            <label class="block">Country</label>
            <div class="border py-2 px-4 rounded w-full">{{ $country?->name }}</div>
        </div>

        @if(!is_null($client->address))
        <div>
            <label for="form_address" class="block">Address</label>
            <div class="border py-2 px-4 rounded w-full">{{ $client->address }}</div>
        </div>
        @endif
    </div>
@endsection
