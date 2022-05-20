@extends('hito-admin::_layout')

@section('title', 'Clients')

@section('actions')
    @can('create', \App\Models\Client::class)
        <a href="{{ route('admin.clients.create') }}"
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
            <th>Projects</th>
            <th></th>
            </thead>

            @foreach($clients as $client)
                <tr class="hover:bg-gray-100">
                    <td class="p-2 text-center">{{ $client->name }}</td>
                    <td class="p-2 text-center">{{ $client->country ? $client->country->name : 'No country selected' }}</td>
                    <td class="p-2 text-center">{{ $client->address ?: '-' }}</td>
                    <td class="p-2">
                        @if($client->projects->count())
                            <div class="space-x-2">
                                @foreach($client->projects as $project)
                                    <a href="{{ route('admin.projects.show', $project->id) }}"
                                       class="py-1 px-2 inline-block bg-green-300 rounded text-sm font-bold text-green-700">{{ $project->name }}</a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center">
                                <b>No projects assigned</b>
                            </div>
                        @endif
                    </td>
                    <td class="p-2 text-right">
                        @can('view', $client)
                            <a href="{{ route('admin.clients.show', $client->id) }}" title="Show" data-tooltip
                               class="py-1 px-2 rounded text-sm font-bold uppercase bg-green-600 text-white hover:bg-green-500">
                                <i class="fas fa-eye"></i></a>
                        @endcan
                        @can('update', $client)
                            <a href="{{ route('admin.clients.edit', $client->id) }}" title="Edit" data-tooltip
                               class="py-1 px-2 rounded text-sm font-bold uppercase bg-blue-600 text-white hover:bg-blue-500">
                                <i class="fas fa-edit"></i></a>
                        @endcan
                        @can('delete', $client)
                            <a href="{{ route('admin.clients.delete', $client->id) }}" title="Delete" data-tooltip
                               class="py-1 px-2 rounded text-sm font-bold uppercase bg-red-600 text-white hover:bg-red-500">
                                <i class="fas fa-times"></i></a>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    <div>
        {{ $clients->links() }}
    </div>
@endsection
