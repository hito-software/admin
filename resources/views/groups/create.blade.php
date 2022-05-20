@extends('hito-admin::_layout')

@section('title', 'Create group')

@section('content')
    <x-hito::form action="{{  route('admin.groups.store') }}" method="post">
        @include('hito-admin::groups._form')
        <x-hito::form.submit>Create group</x-hito::form.submit>
    </x-hito::form>
@endsection
