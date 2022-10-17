@extends('hito-admin::_layout')

@section('title', $title)
@section('page-title', $pageTitle)

@section('actions')
    @foreach($actions as $action)
        @if($action['method'] === 'GET')
            <a href="{{ $action['url'] }}"
               class="hito-admin__resource__header-btn" style="@if(!empty($action['color'])) color: {{ $action['color'] }};
@endif @if(!empty($action['bgColor'])) background-color: {{ $action['bgColor'] }} @endif">
                @if(!empty($action['icon']))
                    <i class="{{ $action['icon'] }}"></i>
                @endif
                <span>{{ $action['label'] }}</span>
            </a>
        @else
            <form action="{{ $action['url'] }}" method="POST">
                @csrf
                @method($action['method'])
                <button type="submit" class="hito-admin__resource__header-btn" style="@if(!empty($action['color'])) color: {{ $action['color'] }};
@endif @if(!empty($action['bgColor'])) background-color: {{ $action['bgColor'] }} @endif">
                    @if(!empty($action['icon']))
                        <i class="{{ $action['icon'] }}"></i>
                    @endif
                    <span>{{ $action['label'] }}</span>
                </button>
            </form>
        @endif
    @endforeach

    @yield('actions')
@overwrite
