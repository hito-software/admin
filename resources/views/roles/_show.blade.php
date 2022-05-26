<x-hito::Card>
    <div class="hito-admin__resource__wrapper">
        <x-hito::Form.Input title="Name" name="name" value="{{ $role->name }}" disabled />
        <x-hito::Form.Input title="Description" name="description" value="{{ $role->description }}" disabled />
        <x-hito::Form.BooleanSelect title="Is required" name="required" :value="$role->required"
                                    disabled />
    </div>
</x-hito::Card>
