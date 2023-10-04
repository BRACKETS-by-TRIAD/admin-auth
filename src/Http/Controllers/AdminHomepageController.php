<?php

namespace Brackets\AdminAuth\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class AdminHomepageController extends Controller
{
    /**
     * Display default admin home page
     *
     * @return View
     */
    public function index(): View
    {
        $quote = 'Well begun is half done.';
        $quoteAuthor = 'Aristotle';

        return view('brackets/admin-auth::admin.homepage.index', [
            'quote' => $quote,
            'quoteAuthor' => $quoteAuthor
        ]);
    }
}
