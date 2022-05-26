<div>
    <div class="hito-admin__resource__index__item-title">{{ $user->fullName }}</div>
    <div class="hito-admin__resource__index__item-pills">
        <span class="hito-admin__resource__index__pill hito-admin__resource__index__pill--green"
        title="Email address" data-tooltip>
            <i class="fa-solid fa-envelope"></i>
            <span>{{ $user->email }}</span>
        </span>
    </div>
</div>
