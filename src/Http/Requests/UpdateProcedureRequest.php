<?php

namespace Hito\Admin\Http\Requests;

class UpdateProcedureRequest extends StoreProcedureRequest
{
    public function authorize()
    {
        return true;
    }
}
