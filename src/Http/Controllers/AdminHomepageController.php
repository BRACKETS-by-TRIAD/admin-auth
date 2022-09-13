<?php

namespace Brackets\AdminAuth\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Inspiring;
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
        $inspiringQuote = Inspiring::quotes()->random();

        [$quote, $quoteAuthor] = explode(' - ', $inspiringQuote);

        return view('brackets/admin-auth::admin.homepage.index', [
            'quote' => $quote,
            'quoteAuthor' => $quoteAuthor
        ]);
    }
}
