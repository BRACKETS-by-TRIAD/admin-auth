<?php

namespace Brackets\AdminAuth\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class AdminHomepageController extends Controller
{
    /**
     * Display default admin home page
     *
     * @return Factory|View
     */
    public function index()
    {
        $quote = 'Well begun is half done.';
        $quoteAuthor = 'Aristotle';

        return view('brackets/admin-auth::admin.homepage.index', [
            'quote' => $quote,
            'quoteAuthor' => $quoteAuthor
        ]);
    }
}
