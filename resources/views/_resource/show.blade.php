@extends('hito-admin::_layout')

@section('title', $title)
@section('page-title', $pageTitle)

@section('actions')
    @if(!empty($editUrl))
        <a href="{{ $editUrl }}"
           class="hito-admin__resource__header-btn hito-admin__resource__header-btn--edit">
            <i class="fas fa-edit"></i>
            <span>{{ __('hito-admin::resource.edit') }}</span>
        </a>
    @endif

    @if(!empty($deleteUrl))
        <a href="{{ $deleteUrl }}"
           class="hito-admin__resource__header-btn hito-admin__resource__header-btn--delete">
            <i class="fas fa-trash"></i>
            <span>{{ __('hito-admin::resource.delete') }}</span>
        </a>
    @endif
@endsection

@section('content')
    <div class="hito-admin__resource__container">
        @if (session('failed'))
            <x-hito::alert type="danger">{!! session('failed') !!}</x-hito::alert>
        @endif

        @if (session('success'))
            <x-hito::alert type="success">{!! session('success') !!}</x-hito::alert>
        @endif

        {!! $view !!}

        @if (isset($model) &&
            method_exists($model, 'activities') &&
            auth()->user()->can('activityLogs', [$entityClass, $model]))
            @include(
                'admin._shared.activity-list',
                [
                    'type' => strtolower(
                        trans_choice(
                            'activity-log.models.' . get_class($model),
                            1
                        )
                    ),
                    'subject' => $model->id,
                    'subtitle' => __('hito-admin::resource.last-n-activities', [
                        'count' => 20,
                    ]),
                    'activities' => $model->activities()->orderByDesc('created_at')->limit(20)->get(),
                ]
            )
        @endif
    </div>
@endsection
