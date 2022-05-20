@extends('hito-admin::_layout')

@section('title', 'Create client')

@section('content')
    <x-hito::form action="{{  route('admin.clients.store') }}" method="post">
        @include('hito-admin::clients._form')
        <x-hito::form.submit>Create client</x-hito::form.submit>
    </x-hito::form>
@endsection
