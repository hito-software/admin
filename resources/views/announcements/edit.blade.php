@extends('hito-admin::_layout')

@section('title', 'Edit announcement')

@section('content')
    <x-hito::Form action="{{ route('admin.announcements.update', $announcement->id) }}" method="patch">
        @include('hito-admin::announcements._form', ['submitButton' => 'Update'])
        <x-hito::form.submit>Update announcement</x-hito::form.submit>
    </x-hito::Form>
@endsection
