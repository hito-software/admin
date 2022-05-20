@extends('hito-admin::_layout')

@section('title', 'Procedures')

@section('actions')
    @can('create', \App\Models\Procedure::class)
        <a href="{{ route('admin.procedures.create') }}"
           class="bg-blue-500 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
            <i class="fas fa-plus"></i> Create
        </a>
    @endcan
@endsection

@section('content')

    <turbo-echo-stream-source channel="procedures"></turbo-echo-stream-source>
    <div class="my-4 bg-white p-4 rounded-lg shadow">
        @if(session('success'))
            <div class="py-4 px-6 bg-green-600 text-white rounded font-bold mb-5">{{ session('success') }}</div>
        @endif

        <turbo-frame id="procedures" target="_top">
            <table class="w-full">
                <thead>
                <th>Name</th>
                <th>Locations</th>
                <th>Status</th>
                <th>Created at</th>
                <th></th>
                </thead>

                @foreach($procedures as $procedure)
                    <tr class="hover:bg-gray-100">
                        <td class="p-2 text-center">{{ $procedure->name }}</td>
                        <td class="p-2 text-center">
                            <div class="flex flex-wrap gap-2">
                                @forelse($procedure->locations as $location)
                                    <a class="py-1 px-2 bg-blue-500 rounded text-sm uppercase font-bold text-white"
                                       href="{{ route('admin.locations.show', $location->id) }}">{{ $location->name }}</a>
                                @empty
                                    <span class="py-1 px-2 bg-green-500 rounded text-sm uppercase font-bold text-white">All locations</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="p-2 text-center">{{ $procedure->status }}</td>
                        <td class="p-2 text-center">{{ $procedure->created_at }}</td>
                        <td class="p-2 text-right">
                            @can('view', $procedure)
                                <a href="{{ route('admin.procedures.show', $procedure->id) }}" title="Show" data-tooltip
                                   class="py-1 px-2 rounded text-sm font-bold uppercase bg-green-600 text-white hover:bg-green-500">
                                    <i class="fas fa-eye"></i></a>
                            @endcan
                            @can('update', $procedure)
                                <a href="{{ route('admin.procedures.edit', $procedure->id) }}" title="Edit" data-tooltip
                                   class="py-1 px-2 rounded text-sm font-bold uppercase bg-blue-600 text-white hover:bg-blue-500">
                                    <i class="fas fa-edit"></i></a>
                            @endcan
                            @can('delete', $procedure)
                                <a href="{{ route('admin.procedures.delete', $procedure->id) }}" title="Delete" data-tooltip
                                   class="py-1 px-2 rounded text-sm font-bold uppercase bg-red-600 text-white hover:bg-red-500">
                                    <i class="fas fa-times"></i></a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </table>
        </turbo-frame>
    </div>

    <div>
        {{ $procedures->links() }}
    </div>
@endsection
