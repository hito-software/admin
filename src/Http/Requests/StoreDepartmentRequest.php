<?php

namespace Hito\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDepartmentRequest extends FormRequest
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
                'max:255',
                Rule::unique('departments')->withoutTrashed()
            ],
            'description' => 'required|max:255',
            'members' => 'nullable|array',
            'members.*' => 'uuid'
        ];
    }
}
