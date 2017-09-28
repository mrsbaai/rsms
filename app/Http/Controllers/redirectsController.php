<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class redirectsController extends Controller
{
    public function locations()
    {
        return redirect('/faqs', 301);
    }
}
