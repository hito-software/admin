<x-hito::Form.Input title="Name" name="name" :required="true" value="{{ $team->name }}" />
<x-hito::Form.Input title="Description" name="description" type="textarea" value="{{ $team->description }}" />
<x-hito::Form.Select title="Projects" name="projects" multiple :value="$team->projects?->pluck('id')->toArray()"
    :items="$projects" />

@if($roles->count())
@can('team.manage-users', \App\Models\Team::class)
<div>
    <label class="block">Members</label>

    <div class="space-y-4 mt-2">
        @foreach($roles->sortByDesc('required') as $role)
        <div class="border p-4">
            <label for="form_roles-{{ $role->id }}" class="block">{{ $role->name }} @if($role->required)
                * @endif</label>
            <select multiple name="roles_{{ $role->id }}[]" id="form_roles-{{ $role->id }}" data-role
                class="border py-2 px-4 rounded w-full">
                @foreach($users as $user)
                <option value="{{ $user['value'] }}" @if($members->contains(fn($value) => $value['user_id'] ===
                    $user['value']
                    && $value['role_id'] === $role->id)) selected @endif>{{ $user['label'] }}</option>
                @endforeach
            </select>

            <x-hito::form.error name="roles_{{ $role->id }}"></x-hito::form.error>

            @if($role->required && !$members->contains(fn($value) => $value['role_id'] === $role->id))
                <x-hito::alert type="warn">This role requires at least one member.</x-hito::alert>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endcan
@endif

@push('js')
<script>
    (function () {
            document.addEventListener('turbo:load', function () {
                @foreach($roles as $role)
                new Choices('#form_roles-{{ $role->id }}', {
                    removeItemButton: true
                })
                @endforeach
            }, {
                once: true
            });
        })();
</script>
@endpush
