@extends('hito-admin::_layout')

@section('title', 'Edit department')

@section('content')
    <x-hito::Form action="{{  route('admin.departments.update', $department->id) }}" method="patch">
        @include('hito-admin::departments._form')
        <x-hito::form.submit>Update department</x-hito::form.submit>
    </x-hito::Form>
@endsection
