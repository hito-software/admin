<x-hito::Card>
    <div class="hito-admin__resource__wrapper">
        <x-hito::Form.Input title="Name" name="name" value="{{ $user->name }}" disabled />
        <x-hito::Form.Input title="Surname" name="surname" value="{{ $user->surname }}" disabled />
        <x-hito::Form.Input title="Email address" name="email" value="{{ $user->email }}" disabled />
        <x-hito::Form.Input title="Phone Number" name="phone" value="{{ $user->getContact('phone') }}" disabled />
        <x-hito::Form.Input title="Whatsapp" name="whatsapp" value="{{ $user->getContact('whatsapp') }}" disabled />
        <x-hito::Form.Input title="Telegram" name="telegram" value="{{ $user->getContact('telegram') }}" disabled />
        <x-hito::Form.Input title="Skype" name="skype" value="{{ $user->getContact('skype') }}" disabled />
        <x-hito::Form.DatePicker title="Birthdate" name="birthdate" :value="$user->birthdate?->format('Y-m-d')" disabled />
        <x-hito::Form.Select.Location name="location" :value="$user->location_id" disabled />
        <x-hito::Form.Select.Timezone name="timezone" :value="$user->timezone_id" disabled />

        @can('manage-groups', \App\Models\User::class)
            @if(!empty($groups))
                <x-hito::Form.Select title="Groups" name="groups" multiple
                                     :value="$user->groups?->pluck('id')->toArray()" :items="$groups" disabled />
            @endif
        @endcan

        @can('manage-permissions', \App\Models\User::class)
            @if(!empty($permissions))
                <x-hito::Form.Select title="Permissions" name="permissions" multiple
                                     :value="$user->permissions?->pluck('id')->toArray()" :items="$permissions" disabled />
            @endif
        @endcan
    </div>
</x-hito::Card>

@push('js')
    <script>
        (function () {
            document.addEventListener('turbo:load', function () {
                @if(!empty($groups))
                new Choices('#form_groups', {
                    removeItemButton: true
                });
                @endif

                @if(!empty($permissions))
                new Choices('#form_permissions', {
                    removeItemButton: true
                });
                @endif
            }, {
                once: true
            });
        })();
    </script>
@endpush
