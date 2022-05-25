<x-hito::Card>
    <div class="hito-admin__resource__wrapper">
        <x-hito::Form.Input title="Name" name="name" value="{{ $announcement->name }}" disabled/>
        <x-hito::Form.Input title="Description" name="description" type="textarea" :value="$announcement->description"
                            disabled/>
        <x-hito::Form.Input title="Publish at" name="published_at"
                            value="{{ $announcement->published_at?->format('Y-m-d H:i') }}" disabled/>
        <x-hito::Form.Input title="Pin start at" name="pin_start_at"
                            value="{{ $announcement->pin_start_at?->format('Y-m-d H:i') }}" disabled/>
        <x-hito::Form.Input title="Pin end at" name="pin_end_at"
                            value="{{ $announcement->pin_end_at?->format('Y-m-d H:i') }}" disabled/>
        <x-hito::Form.Select.Location name="locations" multiple
                                      :value="$announcement->locations?->pluck('id')->toArray()" disabled/>
    </div>
</x-hito::Card>

<x-hito::Card>
    <div class="hito-admin__resource__wrapper">
        <div class="text-xl">{{ __('app.content') }}</div>
        <hr />
        <div class="prose">
            {!! $announcement->content !!}
        </div>
    </div>
</x-hito::Card>
