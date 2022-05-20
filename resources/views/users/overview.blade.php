@extends('hito-admin::_layout')

@section('title', 'User overview')

@section('content')
<div class="grid grid-cols-2 gap-4">
    <div class="w-full bg-blue-500 shadow-2xl rounded-lg mx-auto text-center py-12 mt-4">
        <h2 class="text-4xl leading-10 font-bold tracking-tight text-white">
            Exit intranet
        </h2>
        <div class="mt-8 flex justify-center">
            <div class="inline-flex rounded-md bg-white shadow">
                <form action="{{ route('admin.logout') }}" method="post">
                    @csrf
                    <button type="submit" class="text-gray-700 font-bold py-2 px-6">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="w-full bg-blue-500 shadow-2xl rounded-lg mx-auto text-center py-12 mt-4">
        <h2 class="text-4xl leading-10 font-bold tracking-tight text-white">
            Manage my notifications
        </h2>
        <div class="mt-8 flex justify-center">
            <div class="inline-flex rounded-md bg-white shadow">
                <a href="#" class="text-gray-700 font-bold py-2 px-6">
                    Notifications settings
                </a>
            </div>
        </div>
    </div>
    @if(auth()->user()->can('update', auth()->user()))
    <div class="w-full bg-blue-500 shadow-2xl rounded-lg mx-auto text-center py-12 mt-4">
        <h2 class="text-4xl leading-10 font-bold tracking-tight text-white">
            Manage my profile
        </h2>
        <div class="mt-8 flex justify-center">
            <div class="inline-flex rounded-md bg-white shadow">
                <a href="{{ route('admin.users.edit-profile') }}" class="text-gray-700 font-bold py-2 px-6">
                    Edit profile
                </a>
            </div>
        </div>
    </div>
    @endif
    @if(auth()->user()->can('update', auth()->user()))
    <div class="w-full bg-blue-500 shadow-2xl rounded-lg mx-auto text-center py-12 mt-4">
        <h2 class="text-4xl leading-10 font-bold tracking-tight text-white">
            Change Password
        </h2>
        <div class="mt-8 flex justify-center">
            <div class="inline-flex rounded-md bg-white shadow">
                <a href="{{ route('admin.users.edit-password') }}" class="text-gray-700 font-bold py-2 px-6">
                    Update password
                </a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
