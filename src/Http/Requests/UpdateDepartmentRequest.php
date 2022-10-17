<?php

namespace Hito\Admin\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateDepartmentRequest extends StoreDepartmentRequest
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
                'max:255',
                Rule::unique('departments')->ignoreModel($this->department)->withoutTrashed()
            ]
        ];
    }
}
