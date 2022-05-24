<?php

namespace Hito\Admin\Http\Requests;

class UpdateAnnouncementRequest extends StoreAnnouncementRequest
{
    public function authorize()
    {
        return true;
    }
}
