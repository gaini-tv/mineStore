<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function history()
    {
        return view('pages.history');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function shipping()
    {
        return view('pages.shipping');
    }

    public function returns()
    {
        return view('pages.returns');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function legal()
    {
        return view('pages.legal');
    }
}
