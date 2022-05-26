<div>
    <div class="hito-admin__resource__index__item-title">{{ $team->name }}</div>
    <div class="hito-admin__resource__index__item-pills">
        <span class="hito-admin__resource__index__pill hito-admin__resource__index__pill--green"
              title="Users" data-tooltip>
            <i class="fa-solid fa-user"></i>
            <span>{{ $team->users->count() }} users</span>
        </span>
        <span class="hito-admin__resource__index__pill hito-admin__resource__index__pill--blue"
              title="Projects" data-tooltip>
            <i class="fa-solid fa-user"></i>
            <span>{{ $team->projects->count() }} projects</span>
        </span>
    </div>
</div>
