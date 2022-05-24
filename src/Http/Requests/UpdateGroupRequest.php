<?php

namespace Hito\Admin\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateGroupRequest extends StoreGroupRequest
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
                Rule::unique('departments')->ignoreModel($this->group)
            ],
            'description' => 'max:255',
            'users' => 'nullable|array',
            'users.*' => 'uuid',
            'permissions' => 'nullable|array',
            'permissions.*' => 'uuid'
        ];
    }
}
