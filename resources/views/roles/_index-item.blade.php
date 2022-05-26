<div>
    <div class="hito-admin__resource__index__item-title">{{ $role->name }}</div>
    <div class="hito-admin__resource__index__item-pills">
        @if($role->required)
            <span class="hito-admin__resource__index__pill hito-admin__resource__index__pill--blue">
                <span>Required</span>
            </span>
        @endif
    </div>
</div>
