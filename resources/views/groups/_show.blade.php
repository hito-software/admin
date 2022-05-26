<x-hito::Card>
    <div class="hito-admin__resource__wrapper">
        <x-hito::Form.Input title="Name" name="name" value="{{ $group->name }}" disabled />
        <x-hito::Form.Input title="Description" name="description" value="{{ $group->description }}" disabled />
        @can('group.manage-users', \App\Models\Group::class)
            <x-hito::Form.Select title="Users" name="users" multiple
                                 :value="$group->users->pluck('id')->toArray()" :items="$users" disabled />
        @endcan
        <x-hito::Form.Select title="Permissions" name="permissions" multiple
                             :value="$group->permissions->pluck('id')->toArray()" :items="$permissions" disabled />
    </div>
</x-hito::Card>
