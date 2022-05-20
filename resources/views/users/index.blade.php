@extends('hito-admin::_layout')

@section('title', 'Users')

@section('actions')
    @can('create', \App\Models\User::class)
        <a href="{{ route('admin.users.create') }}"
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
            <th>Surname</th>
            <th>Email address</th>
            <th>Groups</th>
            <th></th>
            </thead>

            @foreach($users as $user)
                <tr class="hover:bg-gray-100">
                    <td class="p-2 text-center">{{ $user->name }}</td>
                    <td class="p-2 text-center">{{ $user->surname }}</td>
                    <td class="p-2 text-center">{{ $user->email }}</td>
                    <td class="p-2 text-center">
                        @if($user->groups->count())
                            <div class="text-center">
                                @foreach($user->groups as $group)
                                    <a href="{{ route('admin.groups.show', $group->id) }}"
                                       class="py-1 px-2 block bg-green-300 rounded text-sm font-bold text-green-700">{{ $group->name }}</a>
                                @endforeach
                            </div>
                        @else
                            <b>No groups assigned</b>
                        @endif
                    </td>
                    <td class="p-2 text-right">
                        @can('view', $user)
                            <a href="{{ route('admin.users.show', $user->id) }}" title="Show" data-tooltip
                               class="py-1 px-2 rounded text-sm font-bold uppercase bg-green-600 text-white hover:bg-green-500">
                                <i class="fas fa-eye"></i></a>
                        @endcan
                        @can('update', $user)
                            <a href="{{ route('admin.users.edit', $user->id) }}" title="Edit" data-tooltip
                               class="py-1 px-2 rounded text-sm font-bold uppercase bg-blue-600 text-white hover:bg-blue-500">
                                <i class="fas fa-edit"></i></a>
                        @endcan
                        @can('delete', $user)
                            <a href="{{ route('admin.users.delete', $user->id) }}" title="Delete" data-tooltip
                               class="py-1 px-2 rounded text-sm font-bold uppercase bg-red-600 text-white hover:bg-red-500">
                                <i class="fas fa-times"></i></a>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    <div>
        {{ $users->links() }}
    </div>
@endsection
