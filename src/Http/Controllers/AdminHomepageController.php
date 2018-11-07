<?php

namespace Brackets\AdminAuth\Http\Controllers;

use Illuminate\Foundation\Inspiring;

class AdminHomepageController extends Controller
{
    /**
     * Display default admin home page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('brackets/admin-auth::admin.homepage.index', [
            'inspiration' => Inspiring::quote()
        ]);
    }

}
