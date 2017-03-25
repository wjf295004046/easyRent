<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandlordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('cert');
    }
    public function index() {
        echo 'test';die;
        return view('');
    }
}
