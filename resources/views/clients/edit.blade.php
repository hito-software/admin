@extends('hito-admin::_layout')

@section('title', 'Edit client')

@section('content')
    <x-hito::form action="{{ route('admin.clients.update', $client->id) }}" method="patch">
        @include('hito-admin::clients._form')
        <x-hito::form.submit>Update client</x-hito::form.submit>
    </x-hito::form>
@endsection
