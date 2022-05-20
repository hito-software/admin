<x-hito::Form.Input title="Name" name="name" :required="true" value="{{ $project->name }}" />
<x-hito::Form.Select title="Client" name="client" :required="true" value="{{ $project->client_id }}"
    placeholder="Select client" :items="$clients" />
<x-hito::Form.Select title="Country" name="country" value="{{ $project->country_id }}" placeholder="Select country"
    :items="$countries" />
<x-hito::Form.Input title="Address" name="address" value="{{ $project->address }}" />
@can('project.manage-teams', \App\Models\Project::class)
<x-hito::Form.Select title="Teams" name="teams[]" multiple :value="$project->teams->pluck('id')->toArray()" :items="$teams" />
@endcan
<x-hito::Form.Input title="Description" name="description" type="textarea" value="{{ $project->description }}" />

@if($roles->count() && $project->exists)
<x-hito::form.group>
    @if(!empty($title))
    <x-hito::form.label>Members</x-hito::form.label>
    @endif

    <div class="space-y-4 mt-2">
        @foreach($roles->sortByDesc('required') as $role)
        <div class="border p-4">
            <x-hito::form.label :required="$role->required" for="form_roles-{{ $role->id }}">{{ $role->name }}</x-hito::form.label>

            <select multiple name="roles_{{ $role->id }}[]" id="form_roles-{{ $role->id }}"
                class="border py-2 px-4 rounded w-full">
                @foreach($users as $user)
                <option value="{{ $user['value'] }}" @if($members->contains(fn($value) => $value['user_id'] ===
                    $user['value']
                    && $value['role_id'] === $role->id)) selected @endif>{{ $user['label'] }}</option>
                @endforeach
            </select>
            <x-hito::form.error name="roles_{{ $role->id }}"></x-hito::form.error>
            @if($role->required && !$members->contains(fn($value) => $value['role_id'] === $role->id))
            <div class="rounded bg-yellow-500 py-2 px-4 text-white font-bold">This role requires at
                least one member.
            </div>
            @endif
        </div>
        @endforeach
    </div>
</x-hito::form.group>
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
