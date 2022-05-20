@extends('hito-admin::_layout')

@section('title', 'Create announcement')

@section('content')
    <x-hito::Form action="{{  route('admin.announcements.store') }}" method="post">
        @include('hito-admin::announcements._form')
        <x-hito::form.submit>Create announcement</x-hito::form.submit>
    </x-hito::Form>
@endsection
