<x-hito::Card>
    <div class="hito-admin__resource__wrapper">
        <x-hito::Form.Input title="Name" name="name" value="{{ $team->name }}" disabled/>
        <x-hito::Form.Input title="Description" name="description" type="textarea" value="{{ $team->description }}"
                            disabled/>
        <x-hito::Form.Select title="Projects" name="projects" multiple :value="$team->projects?->pluck('id')->toArray()"
                             :items="$projects" disabled/>

        @if($roles->count() && !empty($members))
            @can('team.manage-users', \App\Models\Team::class)
                <div>
                    <label class="block">Members</label>

                    <div class="space-y-4 mt-2">
                        @foreach($roles->sortByDesc('required') as $role)
                            <div class="border p-4">
                                <label for="form_roles-{{ $role->id }}"
                                       class="block">{{ $role->name }} @if($role->required)
                                        *
                                    @endif</label>
                                <select multiple name="roles_{{ $role->id }}[]" id="form_roles-{{ $role->id }}"
                                        data-role
                                        class="border py-2 px-4 rounded w-full" disabled>
                                    @foreach($users as $user)
                                        <option value="{{ $user['value'] }}" @if($members->contains(fn($value) => $value['user_id'] ===
                    $user['value']
                    && $value['role_id'] === $role->id)) selected @endif>{{ $user['label'] }}</option>
                                    @endforeach
                                </select>

                                @error("roles_{$role->id}")
                                <p class="font-bold text-red-500">{{ $message }}</p>
                                @enderror

                                @if($role->required && !$members->contains(fn($value) => $value['role_id'] === $role->id))
                                    <div class="rounded bg-yellow-500 py-2 px-4 text-white font-bold">This role requires
                                        at
                                        least one member.
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endcan
        @endif
    </div>
</x-hito::Card>

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
