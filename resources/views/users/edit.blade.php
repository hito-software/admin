@extends('hito-admin::_layout')

@section('title', 'Edit user')

@section('actions')
    @if(auth()->user()->can('update', $user))
        <form action="{{ route('admin.users.reset-password', $user->id) }}" method="post">
            @csrf
            <button type="submit"
                    class="bg-blue-500 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
                <i class="fas fa-unlock"></i> Reset Password
            </button>
        </form>
    @endif
@endsection

@section('content')
    <x-hito::form action="{{ route('admin.users.update', $user->id) }}" method="patch">
        @include('hito-admin::users._form')
        <x-hito::form.submit>Update user</x-hito::form.submit>
    </x-hito::form>
@endsection
