<x-hito::Form.Input title="Name" name="name" :required="true" value="{{ $announcement->name }}" />
<x-hito::Form.Input title="Description" name="description" :required="true" type="textarea" :value="$announcement->description" />
<x-hito::Form.Input title="Content" name="content" :required="true" type="textarea" rich value="{{ $announcement->content }}" />
<x-hito::Form.DateTimePicker title="Publish at" name="published_at" value="{{ $announcement->exists ? $announcement->published_at : Carbon\Carbon::now() }}" :required="true" />
<x-hito::Form.DateTimePicker title="Pin start at" name="pin_start_at" value="{{ $announcement->pin_start_at }}" />
<x-hito::Form.DateTimePicker title="Pin end at" name="pin_end_at" value="{{ $announcement->pin_end_at }}" />
<x-hito::Form.Select.Location name="locations[]" multiple
                        :value="$announcement->locations?->pluck('id')->toArray()" />
