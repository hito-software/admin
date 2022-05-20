@extends('hito-admin::_layout')

@section('title', $team->name)

@section('actions')
    @can('update', $team)
        <a href="{{ route('admin.teams.edit', $team->id) }}"
           class="bg-blue-600 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
            <i class="fas fa-edit"></i> Edit
        </a>
    @endcan

    @can('delete', $team)
        <a href="{{ route('admin.teams.delete', $team->id) }}"
           class="bg-red-600 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
           <i class="fas fa-trash"></i> Delete
        </a>
    @endcan
@endsection

@section('content')
    <div class="space-y-4 my-4 bg-white p-4 rounded-lg shadow">
        <div>
            <label class="block">Name</label>
            <div class="border py-2 px-4 rounded w-full">{{ $team->name }}</div>
        </div>

        <div>
            <label for="form_description" class="block">Description</label>
            @if(is_null($team->description))
                 <x-hito::alert type="warn">Description was not provided</x-hito::alert>
            @else
                 <textarea id="form_description" cols="30" rows="2" class="border py-2 px-4 rounded w-full" disabled>{{ $team->description }}</textarea>
            @endif
        </div>

        @if($projects->count())
        <div>
            <label for="form_projects" class="block">Projects</label>
            <select multiple id="form_projects" class="border py-2 px-4 rounded w-full" disabled>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" selected>{{ $project->name }}</option>
                @endforeach
            </select>
        </div>
        @endif

        @if($members->count())
        <div>
            <label class="block">Members</label>
            <div class="space-y-4 mt-2">
                @foreach($roles->sortByDesc('required') as $role)
                    <div class="border p-4">
                        <label for="form_roles-{{ $role->id }}" class="block">{{ $role->name }}</label>
                        <select multiple id="form_roles-{{ $role->id }}" data-role class="border py-2 px-4 rounded w-full" disabled>
                            @foreach($members as $member)
                                @if($member->role_id === $role->id)
                                    <option value="{{ $member->user->id }}" selected>{{ $member->user->name }} {{ $member->user->surname }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
@endsection

@push('js')
    <script>
        (function () {
            document.addEventListener('turbo:load', function () {
                @foreach($roles as $role)
                new Choices('#form_roles-{{ $role->id }}')
                @endforeach

                new Choices('#form_projects')
            }, {
                once: true
            });
        })();
    </script>
@endpush
