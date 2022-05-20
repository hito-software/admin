@extends('hito-admin::_layout')

@section('title', 'Groups')

@section('actions')
    @can('create', \App\Models\Group::class)
        <a href="{{ route('admin.groups.create') }}"
           class="bg-blue-500 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
            <i class="fas fa-plus"></i> Create
        </a>
    @endcan
@endsection

@section('content')
    <div class="my-4 bg-white p-4 rounded-lg shadow">
        @if(session('success'))
            <div class="py-4 px-6 bg-green-600 text-white rounded font-bold mb-5">{{ session('success') }}</div>
        @endif

        <table class="w-full">
            <thead>
            <th>Name</th>
            <th>Description</th>
            <th>Members</th>
            <th></th>
            </thead>

            @foreach($groups as $group)
                <tr class="hover:bg-gray-100">
                    <td class="p-2 text-center">{{ $group->name }}</td>
                    <td class="p-2 text-center">{{ $group->description }}</td>
                    <td class="p-2 text-center">{{ $group->users->count() }}</td>
                    <td class="p-2 text-right">
                        @can('view', $group)
                            <a href="{{ route('admin.groups.show', $group->id) }}" title="Show" data-tooltip
                               class="py-1 px-2 rounded text-sm font-bold uppercase bg-green-600 text-white hover:bg-green-500">
                                <i class="fas fa-eye"></i></a>
                        @endcan
                        @can('update', $group)
                            <a href="{{ route('admin.groups.edit', $group->id) }}" title="Edit" data-tooltip
                               class="py-1 px-2 rounded text-sm font-bold uppercase bg-blue-600 text-white hover:bg-blue-500">
                                <i class="fas fa-edit"></i></a>
                        @endcan
                        @can('delete', $group)
                            <a href="{{ route('admin.groups.delete', $group->id) }}" title="Delete" data-tooltip
                               class="py-1 px-2 rounded text-sm font-bold uppercase bg-red-600 text-white hover:bg-red-500">
                                <i class="fas fa-times"></i></a>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
