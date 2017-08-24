<?php namespace Brackets\AdminAuth\Http\Controllers;

use Illuminate\Foundation\Inspiring;

class AdminHomepageController extends Controller {

    public function index() {
        return view('brackets/admin-auth::admin.homepage.index', [
            'inspiration' => Inspiring::quote()
        ]);
    }
    
}
