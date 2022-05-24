<x-hito::Form.Input title="Name" name="name" :required="true" value="{{ $group->name }}"/>
<x-hito::Form.Input title="Description" name="description" value="{{ $group->description }}"/>
@can('group.manage-users', \App\Models\Group::class)
    <x-hito::Form.Select title="Users" name="users" multiple
                   :value="$group->users->pluck('id')->toArray()" :items="$users"/>
@endcan
<x-hito::Form.Select title="Permissions" name="permissions" multiple
               :value="$group->permissions->pluck('id')->toArray()" :items="$permissions"/>
