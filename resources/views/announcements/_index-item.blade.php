<div>
    <div class="hito-admin__resource__index__item-title">{{ $announcement->name }}</div>
    <div class="hito-admin__resource__index__item-pills">
        @forelse($announcement->locations as $location)
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
    </div>
</div>
