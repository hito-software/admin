@extends('hito-admin::_layout')

@section('title', $procedure->name)

@section('actions')
    @can('update', $procedure)
        <a href="{{ route('admin.procedures.edit', $procedure->id) }}"
           class="bg-blue-600 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
            <i class="fas fa-edit"></i> Edit
        </a>
    @endcan

    @can('delete', $procedure)
        <a href="{{ route('admin.procedures.delete', $procedure->id) }}"
           class="bg-red-600 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
           <i class="fas fa-trash"></i> Delete
        </a>
    @endcan
@endsection

@section('content')
    <div class="space-y-4 my-4 bg-white p-4 rounded-lg shadow">
        <x-hito::Form.Input title="Name" name="name" :value="$procedure->name" disabled />
        <x-hito::Form.Select.Status name="status" :value="$procedure->status" disabled />
        <x-hito::Form.Input title="Description" name="description" :value="$procedure->description" disabled />
        <x-hito::Form.Select.Location name="locations" multiple disabled
                                      :value="$procedure->locations?->pluck('id')->toArray()" />
    </div>

    <div class="space-y-4 my-4 bg-white p-4 rounded-lg shadow">
        <div class="text-lg">Content</div>
        <div>
            <div class="prose">{!! $procedure->content !!}</div>
        </div>
    </div>
@endsection
