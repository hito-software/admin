<?php

namespace Hito\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGroupRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                Rule::unique('groups')
            ],
            'description' => 'max:255',
            'users' => 'nullable|array',
            'users.*' => 'uuid',
            'permissions' => 'nullable|array',
            'permissions.*' => 'uuid'
        ];
    }
}
