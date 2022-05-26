<x-hito::Card>
    <div class="hito-admin__resource__wrapper">
        <x-hito::Form.Input title="Name" name="name" value="{{ $department->name }}" disabled />
        <x-hito::Form.Input title="Description" name="description" value="{{ $department->description }}" disabled />
        <x-hito::Form.Select title="Members" name="members" multiple
                             :value="$department->users->pluck('id')->toArray()" :items="$users" disabled />
    </div>
</x-hito::Card>
