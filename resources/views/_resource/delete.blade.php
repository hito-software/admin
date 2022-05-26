@extends('hito-admin::_resource._layout')

@section('title', $title ?? __('hito-admin::resource.delete-entity', ['entity' => $entity['singular']]))
@section('page-title', $pageTitle ?? __('hito-admin::resource.delete-entity', ['entity' => $entity['singular']]))

@section('content')
    <div class="hito-admin__resource__container">
        <x-hito::DeleteForm
            :destroyUrl="$destroyUrl"
            :submitButton="$submitButton"

            :cancelUrl="$cancelUrl"
            :cancelButton="$cancelButton"

            :title="$formTitle"
            :description="$formDescription"/>
    </div>
@endsection
