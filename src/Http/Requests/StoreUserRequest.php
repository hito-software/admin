<?php

namespace Hito\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'surname' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users')
            ],
            'birthdate' => 'nullable|date_format:Y-m-d',
            'skype' => 'nullable',
            'whatsapp' => 'nullable',
            'telegram' => 'nullable',
            'location' => 'required|uuid',
            'timezone' => 'required|uuid',
            'phone' => 'nullable|regex:/^([0-9\+\s\(\)]*)$/|min:4|max:25',
            'groups' => 'nullable|array',
            'groups.*' => 'uuid',
            'permissions' => 'nullable|array',
            'permissions.*' => 'uuid'
        ];
    }
}
