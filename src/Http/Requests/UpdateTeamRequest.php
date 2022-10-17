<?php

namespace Hito\Admin\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateTeamRequest extends StoreTeamRequest
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
                Rule::unique('teams')->ignoreModel($this->team)->withoutTrashed()
            ]
        ];
    }
}
