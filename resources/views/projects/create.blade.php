@extends('hito-admin::_layout')

@section('title', 'Create project')

@section('content')
    <x-hito::Form action="{{  route('admin.projects.store') }}" method="post">
        @include('hito-admin::projects._form')
        <x-hito::form.submit>Create project</x-hito::form.submit>
    </x-hito::Form>
@endsection
