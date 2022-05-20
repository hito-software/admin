@extends('hito-admin::_layout')

@section('title', 'Create user')

@section('content')
    <x-hito::form action="{{ route('admin.users.store') }}" method="post">
        @include('hito-admin::users._form')
        <x-hito::form.submit>Create user</x-hito::form.submit>
    </x-hito::form>
@endsection
