@extends('hito-admin::_layout')

@section('title', 'Create procedure')

@section('content')
    <x-hito::Form action="{{ route('admin.procedures.store') }}" method="post">
        @include('hito-admin::procedures._form')
        <x-hito::form.submit>Create procedure</x-hito::form.submit>
    </x-hito::Form>
@endsection
