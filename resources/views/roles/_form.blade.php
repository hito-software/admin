<input type="hidden" name="type" value="{{ $type }}"/>
<x-hito::Form.Input title="Name" name="name" :required="true" value="{{ $role->name }}" />
<x-hito::Form.Input title="Description" name="description" :required="true" value="{{ $role->description }}" />
<x-hito::Form.BooleanSelect title="Is required" name="required" :required="true" : value="{{ $role->required }}"/>
