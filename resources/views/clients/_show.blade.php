<x-hito::Card>
    <div class="hito-admin__resource__wrapper">
        <x-hito::Form.Input title="Name" name="name" value="{{ $client->name }}" disabled />
        <x-hito::Form.Input title="Description" type="textarea" name="description" value="{{ $client->description }}" disabled />
        <x-hito::Form.Select title="Country" name="country" value="{{ $client->country_id }}"
                             placeholder="Select country" :items="$countries" disabled />
        <x-hito::Form.Input title="Address" name="address" value="{{ $client->address }}" disabled />
    </div>
</x-hito::Card>
