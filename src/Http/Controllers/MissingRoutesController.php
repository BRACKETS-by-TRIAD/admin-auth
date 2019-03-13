<?php

namespace Brackets\AdminAuth\Http\Controllers;

use Illuminate\Support\Facades\Redirect;

class MissingRoutesController extends Controller
{
    /**
     * Display default admin home page
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect(): \Illuminate\Http\RedirectResponse
    {
        return Redirect::route('brackets/admin-auth::admin/login');
    }

}
