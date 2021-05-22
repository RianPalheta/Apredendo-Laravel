<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;

class CategoryController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.Categories.index');
    }

    public function create()
    {
        return view('admin.Categories.add');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $cat = Categorie::find($id);
        return view('admin.Categories.edit', [
            'cat' => $cat
        ]);
    }
}
