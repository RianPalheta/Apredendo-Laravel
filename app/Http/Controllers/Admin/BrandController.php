<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.Brands.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.Brands.add');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $brand = Brand::find($id);

        if($brand) {
            return view('admin.Brands.edit', [
                'brand' => $brand
            ]);
        } else {
            return redirect()->route('brands.list');
        }
    }
}
