@extends('hito-admin::_layout')

@section('title', 'Create team')

@section('content')
    <x-hito::Form action="{{  route('admin.teams.store') }}" method="post">
        @include('hito-admin::teams._form')
        <x-hito::form.submit>Create team</x-hito::form.submit>
    </x-hito::Form>
@endsection
