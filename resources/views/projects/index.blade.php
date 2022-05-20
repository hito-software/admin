@extends('hito-admin::_layout')

@section('title', 'Projects')

@section('actions')
    @can('viewAny', \App\Models\Role::class)
        <a href="{{ route('admin.roles.index', ['type' => 'project']) }}"
           class="bg-blue-500 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90 inline-block">
            <i class="fas fa-file-invoice"></i> Manage roles
        </a>
    @endcan

    @can('create', \App\Models\Project::class)
        <a href="{{ route('admin.projects.create') }}"
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
            <th>Client</th>
            <th>Country</th>
            <th>Address</th>
            <th>Teams</th>
            <th></th>
            </thead>

            @foreach($projects as $project)
                <tr class="hover:bg-gray-100">
                    <td class="p-2 text-center">{{ $project->name }}</td>
                    <td class="p-2 text-center">
                        @if(!empty($project->client))
                            <div class="text-center">
                                <a href="{{ route('admin.clients.show', $project->client->id) }}"
                                   class="py-1 px-2 block bg-green-300 rounded text-sm font-bold text-green-700">{{ $project->client->name  }}</a>
                            </div>
                        @else
                            <b>No client assigned</b>
                        @endif
                    </td>
                    <td class="p-2 text-center">
                        @if(!empty($project->country) || !empty($project->client->country))
                            {{ $project->country?->name ?: $project->client->country?->name }}
                        @else
                            -
                        @endif
                    </td>

                    <td class="p-2 text-center">
                        @if(!empty($project->address) || !empty($project->client->address))
                            {{ $project->address ?: $project->client->address }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="p-2 text-center">
                        @if($project->teams->count())
                            <div class="text-center">
                                @foreach($project->teams as $team)
                                    <a href="{{ route('admin.teams.show', $team->id) }}"
                                       class="py-1 px-2 block bg-green-300 rounded text-sm font-bold text-green-700">{{ $team->name }}</a>
                                @endforeach
                            </div>
                        @else
                            <b>No team assigned</b>
                        @endif
                    </td>
                    <td class="p-2 text-right">
                        @can('view', $project)
                            <a href="{{ route('admin.projects.show', $project->id) }}" title="Show" data-tooltip
                               class="py-1 px-2 rounded text-sm font-bold uppercase bg-green-600 text-white hover:bg-green-500">
                                <i class="fas fa-eye"></i></a>
                        @endcan
                        @can('update', $project)
                            <a href="{{ route('admin.projects.edit', $project->id) }}" title="Edit" data-tooltip
                               class="py-1 px-2 rounded text-sm font-bold uppercase bg-blue-600 text-white hover:bg-blue-500">
                                <i class="fas fa-edit"></i></a>
                        @endcan
                        @can('delete', $project)
                            <a href="{{ route('admin.projects.delete', $project->id) }}" title="Delete" data-tooltip
                               class="py-1 px-2 rounded text-sm font-bold uppercase bg-red-600 text-white hover:bg-red-500">
                                <i class="fas fa-times"></i></a>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    <div>
        {{ $projects->links() }}
    </div>
@endsection
