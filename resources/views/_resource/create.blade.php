@extends('hito-admin::_resource._layout')

@section('title', $title ?? __('hito-admin::resource.create-entity', ['entity' => $entity['singular']]))
@section('page-title', $pageTitle ?? __('hito-admin::resource.create-entity', ['entity' => $entity['singular']]))

@section('content')
    <div class="hito-admin__resource__container">
        <x-hito::Card>
            <div class="p-5">
                <x-hito::Form
                    action="{{ $storeUrl ?? null }}"
                    method="post">
                    {!! $view !!}
                    <x-hito::form.submit>
                        {{ $submitButton ?? __('hito-admin::resource.create-entity', ['entity' => $entity['singular']]) }}
                    </x-hito::form.submit>
                </x-hito::Form>
            </div>
        </x-hito::Card>
    </div>
@endsection
