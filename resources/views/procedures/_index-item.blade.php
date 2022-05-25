<div>
    <div class="hito-admin__resource__index__item-title">{{ $procedure->name }}</div>
    <div class="hito-admin__resource__index__item-pills">
        @forelse($procedure->locations as $location)
            <a href="{{ route('admin.locations.show', $location->id) }}"
               class="hito-admin__resource__index__pill hito-admin__resource__index__pill--blue">
                <i class="fa-solid fa-location-dot"></i>
                <span>{{ $location->name }} </span>
            </a>
        @empty
            <span class="hito-admin__resource__index__pill hito-admin__resource__index__pill--green">
                <i class="fa-solid fa-location-check"></i>
                <span>All locations</span>
            </span>
        @endforelse
        <span
                class="hito-admin__resource__index__pill {{ $procedure->status === \Hito\Admin\Enums\Status::PUBLIC ? 'hito-admin__resource__index__pill--green' : 'hito-admin__resource__index__pill--red' }}"
                title="{{ __('app.status') }}" data-tooltip>
            <span>{{ $procedure->status->toString() }} </span>
        </span>
    </div>
</div>
