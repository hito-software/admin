@extends('hito-admin::_layout')

@section('title', 'Edit role')

@section('content')
    <x-hito::Form action="{{ route('admin.roles.update', $role->id) }}" method="patch">
        @include('hito-admin::roles._form', ['submitButton' => 'Update'])
        <x-hito::form.submit>Update role</x-hito::form.submit>
    </x-hito::Form>
@endsection
