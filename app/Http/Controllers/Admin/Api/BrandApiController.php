<?php

namespace App\Http\Controllers\Admin\Api;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class BrandApiController extends Controller
{
    public function get_brands(Request $request) {
        $search['content'] = $request->input('search');

        $qt = intval($request->input('qt', 10));
        $brands = Brand::where('name', 'like', '%'.$search['content'].'%')
            ->orderByDesc('id')
            ->paginate($qt);

        foreach($brands as $key => $item) {
            $t = $this->total_products($item['id']);
            $brands[$key]['total_products'] = $t;
        }

        return response()->json($brands);
    }

    public function store(Request $request)
    {
        $data = $request->only([
            'name'
        ]);

        if( $request->hasFile('img') &&
            $request->file('img')->isValid()) {
            $data['img'] = $request->file('img');
        }

        $validator = Validator::make($data, [
            'img'   => 'image|max:5243',
            'name'  => 'required|string|max:100,unique:brands'
        ]);
        if($validator->fails()) {
            $create['success'] = false;
            $create['message'] = $validator->errors();
        } else {
            $create['success'] = true;
            $create['message'] = '';

            if(!empty($data['img'])) {
                $data['img'] = $this->encodeImg(
                    $request
                    ->file('img')
                    ->path(),
                    'webp',
                    100
                );
            }

            $brand = new Brand;
            foreach($data as $key => $value) {
                $brand->$key = $value;
            }
            $brand->save();
        }

        return response()->json($create);
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::find($id);
        $data = $request->only([
            'name',
            'img'
        ]);

        if($brand) {

            if( $request->hasFile('img') &&
                $request->file('img')->isValid()) {
                $data['img'] = $request->file('img');
            }

            $validator = Validator::make($data, [
                'img'   => 'image|max:5243',
                'name'  => 'string|max:100,unique:brands'
            ]);
            if($validator->fails()) {
                $update['success'] = false;
                $update['message'] = $validator->errors();
            } else {
                $update['success'] = true;
                $update['message'] = '';

                if(!empty($data['img'])) {
                    if($brand->img != 'default.png') {
                        @unlink(public_path('media').'/brands/'.$brand->img);
                    }

                    $data['img'] = $this->encodeImg(
                        $request
                        ->file('img')
                        ->path(),
                        'webp',
                        100
                    );
                }

                foreach($data as $key => $value) {
                    $brand->$key = $value;
                }
                $brand->save();
            }
        } else {
            $update['success'] = false;
            $update['message'] = [
                'not_brand' => 'Marca não encontrada'
            ];
        }

        echo json_encode($update);
        return;
    }

    public function destroy($id)
    {
        if($this->total_products($id) > 0) {
            $delete = [
                'success' => false,
                'message' => [
                    'can_not' => 'Existem produtos cadastrados nessa marca.'
                ]
            ];
        } else {
            $brand = Brand::find($id);
            if($brand->img != 'default.png') {
                @unlink(
                    public_path('media')
                    .'/brands/'
                    .$brand->img
                );
            }
            $brand->delete();
            $delete['success'] = true;
        }

        return response()->json($delete);
    }

    protected function encodeImg($n, $ext, $ql, $w = 300, $h = 300) {
        $image = Image::make($n)->encode($ext, $ql);
        $image->resize($w, $h);
        $img_name = md5(strtotime('now').rand(1000,9999)).'.'.$ext;
        $image->save(public_path('media').'/brands/'.$img_name);
        return $img_name;
    }

    private function total_products($id) {
        return Product::select('id_brand')->where('id_brand', $id)->count();
    }
}
