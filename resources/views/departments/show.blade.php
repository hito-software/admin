@extends('hito-admin::_layout')

@section('title', $department->name)

@section('actions')
    @can('update', $department)
        <a href="{{ route('admin.departments.edit', $department->id) }}"
           class="bg-blue-600 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
            <i class="fas fa-edit"></i> Edit
        </a>
    @endcan

    @can('delete', $department)
        <a href="{{ route('admin.departments.delete', $department->id) }}"
           class="bg-red-600 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
           <i class="fas fa-trash"></i> Delete
        </a>
    @endcan
@endsection

@section('content')
    <div class="space-y-4 my-4 bg-white p-4 rounded-lg shadow">
        <div>
            <label class="block">Name</label>
            <div class="border py-2 px-4 rounded w-full">{{ $department->name }}</div>
        </div>

        <div>
            <label for="form_description" class="block">Description</label>
            <textarea disabled id="form_description" cols="30" rows="2" class="border py-2 px-4 rounded w-full">{{ $department->description }}</textarea>
        </div>

        <div>
            <label for="form_members" class="block">Members</label>
            <select multiple id="form_members" class="border py-2 px-4 rounded w-full" disabled>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" selected>{{ $user->fullname }}</option>
                @endforeach
            </select>
        </div>
    </div>
@endsection

@push('js')
    <script>
        (function () {
            document.addEventListener('turbo:load', function () {
                new Choices('#form_members');
            }, {
                once: true
            });
        })();
    </script>
@endpush
