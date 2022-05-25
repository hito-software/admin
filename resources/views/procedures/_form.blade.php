<x-hito::Form.Input title="Name" name="name" :required="true" :value="$procedure->name" />
<x-hito::Form.Input title="Description" name="description" :required="true" type="textarea" :value="$procedure->description" />
<x-hito::Form.Input title="Content" name="content" :required="true" type="textarea" rich :value="$procedure->content" />
<x-hito::Form.Select.Status name="status" :required="true" :value="$procedure->status" />
<x-hito::Form.Select.Location name="locations" multiple
                              :value="$procedure->locations?->pluck('id')->toArray()" />
