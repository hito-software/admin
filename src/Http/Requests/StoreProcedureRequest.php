<?php

namespace Hito\Admin\Http\Requests;

use Hito\Admin\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProcedureRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'description' => 'required|max:255',
            'content' => 'required',
            'published_at' => 'nullable|date_format:Y-m-d H:i',
            'status' => [
                'required',
                Rule::in(array_map(fn($status) => $status->value, Status::cases()))
            ],
            'locations' => 'nullable|array',
            'locations.*' => 'uuid'
        ];
    }
}
