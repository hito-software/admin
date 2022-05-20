@extends('hito-admin::_layout')

@section('title', 'Roles')

@section('actions')
    @can('create', \App\Models\Role::class)
        <a href="{{ route('admin.roles.create',['type' => $type]) }}"
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
            <th>Required</th>
            <th></th>
            </thead>

            @foreach($roles as $role)
                <tr class="hover:bg-gray-100">
                    <td class="p-2 text-center">{{ $role->name }}</td>
                    <td class="p-2 text-center">{{ $role->required ? 'Yes':'No' }}</td>
                    <td class="p-2 text-right">
                        @can('view', $role)
                            <a href="{{ route('admin.roles.show', $role->id) }}" title="Show" data-tooltip
                               class="py-1 px-2 rounded text-sm font-bold uppercase bg-green-600 text-white hover:bg-green-500">
                                <i class="fas fa-eye"></i></a>
                        @endcan
                        @can('update', $role)
                            <a href="{{ route('admin.roles.edit', $role->id) }}" title="Edit" data-tooltip
                               class="py-1 px-2 rounded text-sm font-bold uppercase bg-blue-600 text-white hover:bg-blue-500">
                                <i class="fas fa-edit"></i></a>
                        @endcan
                        @can('delete', $role)
                            <a href="{{ route('admin.roles.delete', $role->id) }}" title="Delete" data-tooltip
                               class="py-1 px-2 rounded text-sm font-bold uppercase bg-red-600 text-white hover:bg-red-500">
                                <i class="fas fa-times"></i></a>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
