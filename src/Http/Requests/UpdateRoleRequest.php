<?php

namespace Hito\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends StoreRoleRequest
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
                Rule::unique('roles')->ignoreModel($this->role)->where(function ($query) {
                    $query->where('entity_type', $this->role->entity_type);
                })
            ]
        ];
    }
}
