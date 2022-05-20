@extends('hito-admin::_layout')

@section('title', 'Locations')

@section('actions')
    @can('create', \App\Models\Location::class)
        <a href="{{ route('admin.locations.create') }}"
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
            <th>Country</th>
            <th>Address</th>
            <th></th>
            </thead>

            @foreach($locations as $location)
                <tr class="hover:bg-gray-100">
                    <td class="p-2 text-center">{{ $location->name }}</td>
                    <td class="p-2 text-center">{{ $location->country?->name }}</td>
                    <td class="p-2 text-center">{{ $location->address }}</td>
                    <td class="p-2 text-right">
                        @can('view', $location)
                            <a href="{{ route('admin.locations.show', $location->id) }}" title="Show" data-tooltip
                               class="py-1 px-2 rounded text-sm font-bold uppercase bg-green-600 text-white hover:bg-green-500">
                                <i class="fas fa-eye"></i></a>
                        @endcan
                        @can('update', $location)
                            <a href="{{ route('admin.locations.edit', $location->id) }}" title="Edit" data-tooltip
                               class="py-1 px-2 rounded text-sm font-bold uppercase bg-blue-600 text-white hover:bg-blue-500">
                                <i class="fas fa-edit"></i></a>
                        @endcan
                        @can('delete', $location)
                            <a href="{{ route('admin.locations.delete', $location->id) }}" title="Delete" data-tooltip
                               class="py-1 px-2 rounded text-sm font-bold uppercase bg-red-600 text-white hover:bg-red-500">
                                <i class="fas fa-times"></i></a>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    <div>
        {{ $locations->links() }}
    </div>
@endsection
