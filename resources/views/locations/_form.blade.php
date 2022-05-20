<x-hito::Form.Input title="Name" name="name" :required="true" maxlength="100" value="{{ $location->name }}" />
<x-hito::Form.Input title="Description" name="description" maxlength="255" type="textarea" value="{{ $location->description }}" />
<x-hito::Form.Select title="Country" name="country" required="true" value="{{ $location->country_id }}" :items="$countries"
               placeholder="Select country" />
<x-hito::Form.Input title="Address" name="address" required="true" maxlength="150" value="{{ $location->address }}" />
