<div>
    <div class="hito-admin__resource__index__item-title">{{ $location->name }}</div>
    <div class="hito-admin__resource__index__item-pills">
        <span
            class="hito-admin__resource__index__pill hito-admin__resource__index__pill--blue"
            title="{{ __('app.created-at') }}" data-tooltip>
            <i class="fa-solid fa-clock"></i>
            <span>{{ $location->country?->name }} </span>
        </span>
        <span
            class="hito-admin__resource__index__pill hito-admin__resource__index__pill--green"
            title="{{ __('app.start-at') }}" data-tooltip>
            <i class="fa-solid fa-clock"></i>
            <span>{{ $location->address }} </span>
        </span>
    </div>
</div>
