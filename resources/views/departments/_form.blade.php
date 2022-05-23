<x-hito::Form.Input title="Name" name="name" :required="true" value="{{ $department->name }}" />
<x-hito::Form.Input title="Description" name="description" :required="true" value="{{ $department->description }}" />
<x-hito::Form.Select title="Members" name="members" multiple
               :value="$department->users->pluck('id')->toArray()" :items="$users" />
