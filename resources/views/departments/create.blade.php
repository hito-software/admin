@extends('hito-admin::_layout')

@section('title', 'Create department')

@section('content')
    <x-hito::Form action="{{  route('admin.departments.store') }}" method="post">
        @include('hito-admin::departments._form')
        <x-hito::form.submit>Create department</x-hito::form.submit>
    </x-hito::Form>
@endsection
