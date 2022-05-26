<div>
    <div class="hito-admin__resource__index__item-title">{{ $client->name }}</div>
    <div class="hito-admin__resource__index__item-pills">
        <span class="hito-admin__resource__index__pill hito-admin__resource__index__pill--green"
              title="Country" data-tooltip>
            <i class="fa-solid fa-globe"></i>
            <span>{{ $client->country?->name }}</span>
        </span>

        @if(!empty($client->address))
            <span class="hito-admin__resource__index__pill hito-admin__resource__index__pill--blue"
                  title="Address" data-tooltip>
                <i class="fa-solid fa-location-dot"></i>
                <span>{{ $client->address }}</span>
            </span>
        @endif
    </div>
</div>
