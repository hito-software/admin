@extends('hito-admin::_layout')

@section('title', 'Edit procedure')

@section('content')
    <x-hito::Form action="{{ route('admin.procedures.update', $procedure->id) }}" method="patch">
        @include('hito-admin::procedures._form', ['submitButton' => 'Update'])
        <x-hito::form.submit>Update procedure</x-hito::form.submit>
    </x-hito::Form>
@endsection
