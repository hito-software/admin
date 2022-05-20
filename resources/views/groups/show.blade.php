@extends('hito-admin::_layout')

@section('title', $group->name)

@section('actions')
    @can('update', $group)
        <a href="{{ route('admin.groups.edit', $group->id) }}"
           class="bg-blue-600 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
            <i class="fas fa-edit"></i> Edit
        </a>
    @endcan

    @can('delete', $group)
        <a href="{{ route('admin.groups.delete', $group->id) }}"
           class="bg-red-600 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
           <i class="fas fa-trash"></i> Delete
        </a>
    @endcan
@endsection

@section('content')
    <div class="space-y-4 my-4 bg-white p-4 rounded-lg shadow">
        <div>
            <label class="block">Name</label>
            <div class="border py-2 px-4 rounded w-full">{{ $group->name }}</div>
        </div>

        <div>
            <label for="description" class="block">Description</label>
            <textarea disabled id="description" cols="30" rows="2" class="border py-2 px-4 rounded w-full">{{ $group->description }}</textarea>
        </div>

        <div>
            <label for="members" class="block">Members</label>
            <select disabled multiple id="members" class="border py-2 px-4 rounded w-full">
                @foreach($users as $user)
                    <option value="{{ $user->id }}" selected>{{ $user->fullname }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="permissions" class="block">Permissions</label>
            <select disabled multiple id="permissions" class="border py-2 px-4 rounded w-full">
                @foreach($permissions as $permission)
                    <option value="{{ $permission->name }}" selected>{{ $permission->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
@endsection

@push('js')
    <script>
        (function () {
            document.addEventListener('turbo:load', function () {
                new Choices('#members');

                new Choices('#permissions');
            }, {
                once: true
            });
        })();
    </script>
@endpush
