<?php

namespace Hito\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends StoreUserRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            ...parent::rules(),
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignoreModel($this->user)
            ],
        ];
    }
}
