@extends('hito-admin::_layout')

@section('title', 'Edit group')

@section('content')
    <x-hito::form action="{{ route('admin.groups.update', $group->id) }}" method="patch">
        @include('hito-admin::groups._form')
        <x-hito::form.submit>Update group</x-hito::form.submit>
    </x-hito::form>
@endsection
