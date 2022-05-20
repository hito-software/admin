@extends('hito-admin::_layout')

@section('title', 'Edit team')

@section('content')
    <x-hito::Form action="{{ route('admin.teams.update', $team->id) }}" method="patch">
        @include('hito-admin::teams._form')
        <x-hito::form.submit>Update team</x-hito::form.submit>
    </x-hito::Form>
@endsection
