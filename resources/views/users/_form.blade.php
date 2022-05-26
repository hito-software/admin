<x-hito::Form.Input title="Name" name="name" :required="true" value="{{ $user->name }}" />
<x-hito::Form.Input title="Surname" name="surname" :required="true" value="{{ $user->surname }}" />
<x-hito::Form.Input title="Email address" name="email" :required="true" value="{{ $user->email }}" />
<x-hito::Form.Input title="Phone Number" name="phone" value="{{ $user->getContact('phone') }}" />
<x-hito::Form.Input title="Whatsapp" name="whatsapp" value="{{ $user->getContact('whatsapp') }}" />
<x-hito::Form.Input title="Telegram" name="telegram" value="{{ $user->getContact('telegram') }}" />
<x-hito::Form.Input title="Skype" name="skype" value="{{ $user->getContact('skype') }}" />
<x-hito::Form.DatePicker title="Birthdate" name="birthdate" :value="$user->birthdate?->format('Y-m-d')" />
<x-hito::Form.Select.Location name="location" :value="$user->location_id" :required="true"/>
<x-hito::Form.Select.Timezone name="timezone" :value="$user->timezone_id" :required="true"/>

@can('manage-groups', \App\Models\User::class)
    @if(!empty($groups))
        <x-hito::Form.Select title="Groups" name="groups" multiple
                       :value="$user->groups?->pluck('id')->toArray()" :items="$groups" />
    @endif
@endcan

@can('manage-permissions', \App\Models\User::class)
    @if(!empty($permissions))
        <x-hito::Form.Select title="Permissions" name="permissions" multiple
                       :value="$user->permissions?->pluck('id')->toArray()" :items="$permissions" />
    @endif
@endcan

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
