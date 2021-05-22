<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        return view('admin.home');
    }
}
