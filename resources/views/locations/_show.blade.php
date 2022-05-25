<x-hito::Card>
    <div class="hito-admin__resource__wrapper">
        <x-hito::Form.Input title="Name" name="name" value="{{ $location->name }}" disabled />
        <x-hito::Form.Input title="Description" name="description" type="textarea" value="{{ $location->description }}" disabled />
        <x-hito::Form.Input title="Country" name="country" value="{{ $location->country?->name }}" disabled />
        <x-hito::Form.Input title="Address" name="address" value="{{ $location->address }}" disabled />
    </div>
</x-hito::Card>
