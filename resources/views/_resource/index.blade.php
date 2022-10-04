@extends('hito-admin::_resource._layout')

@section('title', $title ?? $entity['plural'] ?? null)
@section('page-title', $pageTitle ?? ucfirst($entity['plural']) ?? null)

@section('actions')
    @if(!empty($createUrl))
    <a href="{{ $createUrl }}"
       class="hito-admin__resource__header-btn hito-admin__resource__header-btn--create">
        <i class="fas fa-plus"></i>
        <span>{{ __('hito-admin::resource.create') }}</span>
    </a>
    @endif
@endsection

@section('content')
    <div class="hito-admin__resource__container">
        <x-hito::Card>
            @if ($hasPagination && $items->hasPages())
                <x-slot name="footerSlot">
                    <div class="w-full">
                        {{ $items->withQueryString()->onEachSide(1)->links() }}
                    </div>
                </x-slot>
            @endif

            <div class="hito-admin__resource__index">
                @if (session('failed'))
                    <x-hito::alert type="danger">{!! session('failed') !!}</x-hito::alert>
                @endif

                @if (session('success'))
                    <x-hito::alert type="success">{!! session('success') !!}</x-hito::alert>
                @endif

                @forelse($processedItems as $i => $item)
                    <div class="hito-admin__resource__index__item">
                        <div class="hito-admin__resource__index__view-container">
                            {!! $item['view'] !!}
                        </div>
                        <div
                            class="hito-admin__resource__index__actions">
                            @if (!empty($item['showUrl']))
                                <a href="{{ $item['showUrl'] }}"
                                    class="hito-admin__resource__index__btn hito-admin__resource__index__btn--open"
                                    title="{{ __('hito-admin::resource.open') }}">
                                    <i class="fas fa-eye"></i>
                                    <span
                                        class="hito-admin__resource__index__btn-label">{{ __('hito-admin::resource.open') }}</span>
                                </a>
                            @endif
                            @if (!empty($item['editUrl']))
                                <a href="{{ $item['editUrl'] }}"
                                    class="hito-admin__resource__index__btn hito-admin__resource__index__btn--edit"
                                    title="{{ __('hito-admin::resource.edit') }}">
                                    <i class="fas fa-edit"></i>
                                    <span
                                        class="hito-admin__resource__index__btn-label">{{ __('hito-admin::resource.edit') }}</span>
                                </a>
                            @endif
                            @if (!empty($item['deleteUrl']))
                                <a href="{{ $item['deleteUrl'] }}"
                                    class="hito-admin__resource__index__btn hito-admin__resource__index__btn--delete"
                                    title="{{ __('hito-admin::resource.delete') }}">
                                    <i class="fas fa-trash"></i>
                                    <span
                                        class="hito-admin__resource__index__btn-label">{{ __('hito-admin::resource.delete') }}</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <x-hito::alert>{{ __('hito-admin::resource.no-items-in-database') }}</x-hito::alert>
                @endforelse
            </div>
        </x-hito::Card>
    </div>
@endsection
