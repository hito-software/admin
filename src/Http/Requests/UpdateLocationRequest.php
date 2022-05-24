<?php

namespace Hito\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLocationRequest extends StoreLocationRequest
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
                'max:100',
                Rule::unique('locations')->ignoreModel($this->location)->withoutTrashed()
            ]
        ];
    }
}
