@extends('hito-admin::_layout')

@section('title', "{$user->name} {$user->surname}")

@section('actions')
    @can('update', $user)
        <a href="{{ route('admin.users.edit', $user->id) }}"
           class="bg-blue-600 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
            <i class="fas fa-edit"></i> Edit
        </a>
    @endcan

    @can('delete', $user)
        <a href="{{ route('admin.users.delete', $user->id) }}"
           class="bg-red-600 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
           <i class="fas fa-trash"></i> Delete
        </a>
    @endcan
@endsection

@section('content')
    <div class="space-y-4 my-4 bg-white p-4 rounded-lg shadow">
        <div>
            <label class="block">Name</label>
            <div class="border py-2 px-4 rounded w-full">{{ $user->name }}</div>
        </div>

        <div>
            <label class="block">Surname</label>
            <div class="border py-2 px-4 rounded w-full">{{ $user->surname }}</div>
        </div>

        <div>
            <label class="block">Email address</label>
            <div class="border py-2 px-4 rounded w-full">{{ $user->email }}</div>
        </div>

        @if(!is_null($user->getContact('phone')))
        <div>
            <label class="block">Phone Number</label>
            <div class="border py-2 px-4 rounded w-full">{{ $user->getContact('phone') }}</div>
        </div>
        @endif

        @if(!is_null($user->getContact('whatsapp')))
            <div>
                <label class="block">Whatsapp</label>
                <div class="border py-2 px-4 rounded w-full">
                    <a href="whatsapp://{{$user->whatsapp}}">{{ $user->getContact('whatsapp') }}</a>
                </div>
            </div>
        @endif

        @if(!is_null($user->getContact('telegram')))
            <div>
                <label class="block">Telegram</label>
                <div class="border py-2 px-4 rounded w-full">
                    <a href="https://t.me/{{$user->telegram}}">{{ $user->getContact('telegram') }}</a>
                </div>
            </div>
        @endif

        @if(!is_null($user->getContact('skype')))
            <div>
                <label class="block">Skype</label>
                <div class="border py-2 px-4 rounded w-full">
                    <a href="skype:{{$user->skype}}?chat">{{ $user->getContact('skype') }}</a>
                </div>
            </div>
        @endif

        @if(!is_null($user->birthdate))
            <div>
                <label class="block">Birthdate</label>
                <div class="border py-2 px-4 rounded w-full">{{ $user->birthdate }}</div>
            </div>
        @endif

        @if(isset($user->location))
            <div>
                <label class="block">Location</label>
                <div class="border py-2 px-4 rounded w-full">{{ $user->location->name }}</div>
            </div>
        @endif

        @if(isset($user->timezone))
            <div>
                <label class="block">Timezone</label>
                <div class="border py-2 px-4 rounded w-full">{{ $user->timezone->name }}</div>
            </div>
        @endif

        @if($groups->isNotEmpty())
        <div>
            <label for="groups" class="block">Groups</label>
            <select multiple id="groups" class="border py-2 px-4 rounded w-full" disabled>
                @foreach($groups as $group)
                    <option value="{{ $group->id }}" selected>{{ $group->name }}</option>
                @endforeach
            </select>
        </div>
        @endif

        @if($permissions->isNotEmpty())
        <div>
            <label for="permissions" class="block">Permissions</label>
            <select multiple id="permissions" class="border py-2 px-4 rounded w-full" disabled>
                @foreach($permissions as $permission)
                    <option value="{{ $permission->name }}" selected>{{ $permission->name }}</option>
                @endforeach
            </select>
        </div>
        @endif
    </div>
@endsection

@push('js')
    <script>
        (function () {
            document.addEventListener('turbo:load', function () {
                @if($groups->isNotEmpty())
                new Choices('#groups');
                @endif

                @if($permissions->isNotEmpty())
                new Choices('#permissions');
                @endif
            }, {
                once: true
            });
        })();
    </script>
@endpush
