<?php

namespace Esa\Helper\Http\Controllers;

class HomeController
{
    public function index()
    {
        return view('esa::layout');
    }
}
