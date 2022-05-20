<x-hito::Form.Input title="Name" name="name" :required="true" value="{{ $client->name }}" />
<x-hito::Form.Input title="Description" type="textarea" name="description" value="{{ $client->description }}" />
<x-hito::Form.Select title="Country" name="country" :required="true" value="{{ $client->country_id }}"
               placeholder="Select country" :items="$countries" />
<x-hito::Form.Input title="Address" name="address" value="{{ $client->address }}" />
