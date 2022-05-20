@extends('hito-admin::_layout')

@section('title', 'Edit project')

@section('content')
    <x-hito::Form action="{{ route('admin.projects.update', $project->id) }}" method="patch">
        @include('hito-admin::projects._form')
        <x-hito::form.submit>Update project</x-hito::form.submit>
    </x-hito::Form>
@endsection
