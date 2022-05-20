@extends('hito-admin::_layout')

@section('title', 'Edit location')

@section('content')
    <x-hito::Form action="{{  route('admin.locations.update', $location->id) }}" method="patch">
        @include('hito-admin::locations._form')
        <x-hito::form.submit>Update location</x-hito::form.submit>
    </x-hito::Form>
@endsection
