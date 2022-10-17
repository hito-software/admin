<?php

namespace Hito\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTeamRequest extends FormRequest
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
                Rule::unique('teams')->withoutTrashed()
            ],
            'description' => 'max:255',
            'projects' => 'nullable|array',
            'projects.*' => 'uuid'
        ];
    }
}
