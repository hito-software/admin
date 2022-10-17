<?php

namespace Hito\Admin\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateProjectRequest extends StoreProjectRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            ...parent::rules(),
            'name' => [
                'required',
                Rule::unique('projects')->ignoreModel($this->project)->withoutTrashed()
            ],
        ];
    }
}
