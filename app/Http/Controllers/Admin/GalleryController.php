<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        return view('admin.Gallery.index');
    }

    public function create()
    {
        //
    }

    public function edit($id)
    {
        //
    }
}
