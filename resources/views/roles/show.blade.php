@extends('hito-admin::_layout')

@section('title', $role->name)

@section('actions')
    @can('update', $role)
        <a href="{{ route('admin.roles.edit', $role->id) }}"
           class="bg-blue-600 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
            <i class="fas fa-edit"></i> Edit
        </a>
    @endcan

    @can('delete', $role)
        <a href="{{ route('admin.roles.delete', $role->id) }}"
           class="bg-red-600 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
           <i class="fas fa-trash"></i> Delete
        </a>
    @endcan
@endsection

@section('content')
    <div class="space-y-4 my-4 bg-white p-4 rounded-lg shadow">
        <div>
            <label for="form_name" class="block">Name</label>
            <div class="border py-2 px-4 rounded w-full">{{ $role->name }}</div>
        </div>

        <div>
            <label for="form_description" class="block">Description</label>
            <textarea id="form_description" cols="30" rows="2" class="border py-2 px-4 rounded w-full" disabled>{{ $role->description }}</textarea>
        </div>

        <div>
            <label for="form_required" class="block">Is required</label>
            <div class="border py-2 px-4 rounded w-full">{{ $role->required ? 'Yes':'No' }}</div>
        </div>
    </div>
@endsection
