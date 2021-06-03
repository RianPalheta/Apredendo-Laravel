<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Categorie;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_products(Request $request)
    {
        $search['content'] = $request->input('search');
        switch($search['content']) {
            case filter_var($search['content'], FILTER_VALIDATE_EMAIL):
                $search['type'] = 'email';
            break;
            case is_string($search['content']):
                $search['type'] = 'name';
            break;
        }

        $qt = intval($request->input('qt', 10));
        $products = Product::select(
            'id',
            'id_category',
            'id_brand',
            'name',
            'stock',
            'price',
            'price_from',
            ) //->where($search['type'], 'like', '%'.$search['content'].'%')
            // ->join('brands', 'products.id_brand', '=', 'brands.id')
            ->orderByDesc('id')
            ->paginate($qt);

        foreach($products as $key => $item) {
            $cat = $this->getCategory($item['id_category']);
            $brand = $this->getBrand($item['id_brand']);
            $products[$key]['name_category'] = $cat['name'];
            $products[$key]['name_brand'] = $brand['name'];
        }

        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function getCategory($id) {
        $cat = Categorie::select('name')->where('id', $id)->first();
        return $cat;
    }
    private function getBrand($id) {
        return Brand::select('name')->where('id', $id)->first();
    }
}
