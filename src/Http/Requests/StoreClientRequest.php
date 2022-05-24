<?php

namespace Hito\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientRequest extends FormRequest
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
                Rule::unique('clients')->withoutTrashed()
            ],
            'description' => 'max:255',
            'country' => 'required|uuid',
            'address' => 'nullable'
        ];
    }
}
