@extends('hito-admin::_layout')

@section('title', 'Teams')

@section('actions')
    @can('viewAny', \App\Models\Role::class)
        <a href="{{ route('admin.roles.index', ['type' => 'team']) }}"
           class="bg-blue-500 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90 inline-block">
            <i class="fas fa-file-invoice"></i> Manage roles
        </a>
    @endcan
    @can('create', \App\Models\Team::class)
        <a href="{{ route('admin.teams.create') }}"
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
            <th>Members</th>
            <th>Projects</th>
            <th></th>
            </thead>

            @foreach($teams as $team)
                <tr class="hover:bg-gray-100">
                    <td class="p-2 text-center">{{ $team->name }}</td>
                    <td class="p-2 text-center">{{ $team->members->groupBy('user_id')->count() }}</td>
                    <td class="p-2 text-center">
                        @if($team->projects->count())
                            <div class="text-center">
                                @foreach($team->projects as $project)
                                    <a href="{{ route('admin.projects.show', $project->id) }}"
                                       class="py-1 px-2 block bg-green-300 rounded text-sm font-bold text-green-700">{{ $project->name }}</a>
                                @endforeach
                            </div>
                        @else
                            <b>Not assigned to any project</b>
                        @endif
                    </td>
                    <td class="p-2 text-right">
                        @can('view', $team)
                            <a href="{{ route('admin.teams.show', $team->id) }}" title="Show" data-tooltip
                               class="py-1 px-2 rounded text-sm font-bold uppercase bg-green-600 text-white hover:bg-green-500">
                                <i class="fas fa-eye"></i></a>
                        @endcan
                        @can('update', $team)
                            <a href="{{ route('admin.teams.edit', $team->id) }}" title="Edit" data-tooltip
                               class="py-1 px-2 rounded text-sm font-bold uppercase bg-blue-600 text-white hover:bg-blue-500">
                                <i class="fas fa-edit"></i></a>
                        @endcan
                        @can('delete', $team)
                            <a href="{{ route('admin.teams.delete', $team->id) }}" title="Delete" data-tooltip
                               class="py-1 px-2 rounded text-sm font-bold uppercase bg-red-600 text-white hover:bg-red-500">
                                <i class="fas fa-times"></i></a>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    <div>
        {{ $teams->links() }}
    </div>
@endsection
