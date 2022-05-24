<?php

namespace Hito\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectRequest extends FormRequest
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
                Rule::unique('projects')->withoutTrashed()
            ],
            'description' => 'max:255',
            'country' => 'nullable',
            'address' => 'nullable',
            'client' => 'required',
            'teams' => 'nullable|array',
            'teams.*' => 'uuid'
        ];
    }
}
