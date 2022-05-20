<?php

namespace Hito\Admin\Http\Controllers;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('hito-admin::dashboard');
    }
}
