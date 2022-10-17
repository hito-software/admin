<?php

namespace Hito\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLocationRequest extends FormRequest
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
                'max:100',
                Rule::unique('locations')->withoutTrashed()
            ],
            'description' => 'nullable|max:255',
            'country' => 'required|uuid',
            'address' => 'required|max:150',
        ];
    }
}
