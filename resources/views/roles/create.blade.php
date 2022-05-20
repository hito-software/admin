@extends('hito-admin::_layout')

@section('title', 'Create role')

@section('content')
    <x-hito::Form action="{{  route('admin.roles.store') }}" method="post">
        @include('hito-admin::roles._form')
        <x-hito::form.submit>Create role</x-hito::form.submit>
    </x-hito::Form>
@endsection
