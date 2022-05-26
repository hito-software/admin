<div>
    <div class="hito-admin__resource__index__item-title">{{ $group->name }}</div>
    <div class="hito-admin__resource__index__item-pills">
        <span class="hito-admin__resource__index__pill hito-admin__resource__index__pill--green"
        title="Users" data-tooltip>
            <i class="fa-solid fa-user"></i>
            <span>{{ $group->users->count() }} users</span>
        </span>
        <span class="hito-admin__resource__index__pill hito-admin__resource__index__pill--blue"
        title="Permissions" data-tooltip>
            <i class="fa-solid fa-user"></i>
            <span>{{ $group->permissions->count() }} permissions</span>
        </span>
    </div>
</div>
