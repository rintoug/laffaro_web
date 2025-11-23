<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function contact()
    {
        return view('front.pages.contact');
    }

    public function privacy()
    {
        return view('front.pages.privacy');
    }
}
