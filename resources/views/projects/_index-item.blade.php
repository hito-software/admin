<div>
    <div class="hito-admin__resource__index__item-title">{{ $project->name }}</div>
    <div class="hito-admin__resource__index__item-pills">
        @if(!empty($project->client))
        <a href="{{ route('admin.clients.show', $project->client->id) }}"
           class="hito-admin__resource__index__pill hito-admin__resource__index__pill--blue"
              title="Client" data-tooltip>
            <i class="fa-solid fa-building"></i>
            <span>{{ $project->client->name }}</span>
        </a>
        @else
            <span class="hito-admin__resource__index__pill hito-admin__resource__index__pill--red"
               title="Client" data-tooltip>
                <i class="fa-solid fa-building"></i>
                <span>No client assigned</span>
            </span>
        @endif
        @if(!empty($project->country) || !empty($project->client->country))
        <span class="hito-admin__resource__index__pill hito-admin__resource__index__pill--green"
                  title="Country" data-tooltip>
            <i class="fa-solid fa-globe"></i>
            <span>{{ $project->country?->name ?: $project->client->country?->name }}</span>
        </span>
        @endif
        @if(!empty($project->address) || !empty($project->client->address))
        <span class="hito-admin__resource__index__pill hito-admin__resource__index__pill--cyan"
                  title="Address" data-tooltip>
            <i class="fa-solid fa-location-dot"></i>
            <span>{{ $project->address ?: $project->client->address }}</span>
        </span>
        @endif
    </div>
</div>
