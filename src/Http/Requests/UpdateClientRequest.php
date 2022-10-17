<?php

namespace Hito\Admin\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateClientRequest extends StoreClientRequest
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
                Rule::unique('clients')->ignoreModel($this->client)->withoutTrashed()
            ]
        ];
    }
}
