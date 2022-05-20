@extends('hito-admin::_layout')

@section('title', 'Import data')

@section('content')
    <div class="space-y-4 my-4 bg-white p-4 rounded-lg shadow">
        <form method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4 my-4">
                @can('create', \App\Models\User::class)
                    <div>
                        <label for="form_users_file" class="block">Users File</label>
                        <input type="file" name="users_file" id="form_users_file"
                               class="border py-2 px-4 rounded w-full"/>
                        @error('users_file') <p>{{ $message }}</p> @enderror
                    </div>
                @endcan
                @can('create', \App\Models\Group::class)
                    <div>
                        <label for="form_groups_file" class="block">Groups File</label>
                        <input type="file" name="groups_file" id="form_groups_file"
                               class="border py-2 px-4 rounded w-full"/>
                        @error('groups_file') <p>{{ $message }}</p> @enderror
                    </div>
                @endcan
                @can('create', \App\Models\Department::class)
                    <div>
                        <label for="form_departments_file" class="block">Departments File</label>
                        <input type="file" name="departments_file" id="form_departments_file"
                               class="border py-2 px-4 rounded w-full"/>
                        @error('departments_file') <p>{{ $message }}</p> @enderror
                    </div>
                @endcan
                @can('create', \App\Models\Client::class)
                    <div>
                        <label for="form_clients_file" class="block">Clients File</label>
                        <input type="file" name="clients_file" id="form_clients_file"
                               class="border py-2 px-4 rounded w-full"/>
                        @error('clients_file') <p>{{ $message }}</p> @enderror
                    </div>
                @endcan
                @can('create', \App\Models\Project::class)
                    <div>
                        <label for="form_projects_file" class="block">Projects File</label>
                        <input type="file" name="projects_file" id="form_projects_file"
                               class="border py-2 px-4 rounded w-full"/>
                        @error('projects_file') <p>{{ $message }}</p> @enderror
                    </div>
                @endcan
                @can('create', \App\Models\Team::class)
                    <div>
                        <label for="form_teams_file" class="block">Teams File</label>
                        <input type="file" name="teams_file" id="form_teams_file"
                               class="border py-2 px-4 rounded w-full"/>
                        @error('teams_file') <p>{{ $message }}</p> @enderror
                    </div>
                @endcan
                @can('create', \App\Models\Role::class)
                    <div>
                        <label for="form_roles_file" class="block">Roles File</label>
                        <input type="file" name="roles_file" id="form_roles_file"
                               class="border py-2 px-4 rounded w-full"/>
                        @error('roles_file') <p>{{ $message }}</p> @enderror
                    </div>
                @endcan
                <div>
                    <button type="submit"
                            class="bg-blue-500 py-2 px-4 rounded uppercase font-bold text-white text-sm hover:bg-opacity-75">
                        Import Data
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
