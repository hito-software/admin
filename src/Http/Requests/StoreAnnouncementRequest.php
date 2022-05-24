<?php

namespace Hito\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnnouncementRequest extends FormRequest
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
            'published_at' => 'required|date_format:Y-m-d H:i',
            'pin_start_at' => 'nullable|date_format:Y-m-d H:i|after_or_equal:published_at|required_unless:pin_end_at,null',
            'pin_end_at' => 'nullable|date_format:Y-m-d H:i|after:pin_start_at',
            'locations' => 'nullable|array',
            'locations.*' => 'uuid'
        ];
    }
}
