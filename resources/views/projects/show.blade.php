@extends('hito-admin::_layout')

@section('title', $project->name)

@section('actions')
    @can('update', $project)
        <a href="{{ route('admin.projects.edit', $project->id) }}"
           class="bg-blue-600 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
            <i class="fas fa-edit"></i> Edit
        </a>
    @endcan

    @can('delete', $project)
        <a href="{{ route('admin.projects.delete', $project->id) }}"
           class="bg-red-600 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
           <i class="fas fa-trash"></i> Delete
        </a>
    @endcan
@endsection

@section('content')
    <div class="space-y-4 my-4 bg-white p-4 rounded-lg shadow">
        <div>
            <label class="block">Client</label>
            <div class="border py-2 px-4 rounded w-full">{{ $client->name }}</div>
        </div>

        <div>
            <label class="block">Name</label>
            <div class="border py-2 px-4 rounded w-full">{{ $project->name }}</div>
        </div>

        <div>
            <label for="form_description" class="block">Description</label>
            @if(is_null($project->description))
                 <x-hito::alert type="warn">Description was not provided</x-hito::alert>
            @else
                 <textarea name="description" id="form_description" cols="30" rows="2" class="border py-2 px-4 rounded w-full" disabled>{{ $project->description }}</textarea>
            @endif
        </div>

        @if(!is_null($country))
        <div>
            <label class="block">Country</label>
            <div class="border py-2 px-4 rounded w-full">{{ $country->name }}</div>
        </div>
        @endif

        @if(!is_null($project->address))
        <div>
            <label class="block">Address</label>
            <div class="border py-2 px-4 rounded w-full">{{ $project->address }}</div>
        </div>
        @endif

        @if($teams->count())
        <div>
            <label for="form_teams" class="block">Teams</label>
            <select multiple name="teams[]" id="form_teams" class="border py-2 px-4 rounded w-full" disabled>
                @foreach($teams as $team)
                    <option value="{{ $team->id }}" selected>{{ $team->name }}</option>
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
                        <select multiple name="roles_{{ $role->id }}[]" id="form_roles-{{ $role->id }}" data-role
                                class="border py-2 px-4 rounded w-full" disabled>
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
                new Choices('#form_teams');

                @foreach($roles as $role)
                new Choices('#form_roles-{{ $role->id }}')
                @endforeach
            }, {
                once: true
            });
        })();
    </script>
@endpush
