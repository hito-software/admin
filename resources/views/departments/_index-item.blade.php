<div>
    <div class="hito-admin__resource__index__item-title">{{ $department->name }}</div>
    <div class="hito-admin__resource__index__item-pills">
        <span class="hito-admin__resource__index__pill hito-admin__resource__index__pill--green"
        title="Users" data-tooltip>
            <i class="fa-solid fa-user"></i>
            <span>{{ $department->users->count() }} users</span>
        </span>
    </div>
</div>
