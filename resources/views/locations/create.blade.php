@extends('hito-admin::_layout')

@section('title', 'Create location')

@section('content')
    <x-hito::Form action="{{  route('admin.locations.store') }}" method="post">
        @include('hito-admin::locations._form')
        <x-hito::form.submit>Create location</x-hito::form.submit>
    </x-hito::Form>
@endsection
