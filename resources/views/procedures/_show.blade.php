<x-hito::Card>
    <div class="hito-admin__resource__wrapper">
        <x-hito::Form.Input title="Name" name="name" :value="$procedure->name" disabled />
        <x-hito::Form.Input title="Description" name="description" type="textarea"
                            :value="$procedure->description" disabled/>
        <x-hito::Form.Select.Status name="status" :value="$procedure->status"/>
        <x-hito::Form.Select.Location name="locations" multiple
                                      :value="$procedure->locations?->pluck('id')->toArray()" disabled/>
    </div>
</x-hito::Card>

<x-hito::Card>
    <div class="hito-admin__resource__wrapper">
        <div class="text-xl">{{ __('app.content') }}</div>
        <hr />
        <div class="prose">
            {!! $procedure->content !!}
        </div>
    </div>
</x-hito::Card>
