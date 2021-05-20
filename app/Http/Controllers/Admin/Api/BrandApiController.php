<?php

namespace App\Http\Controllers\Admin\Api;

use App\Models\Page;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class BrandApiController extends Controller
{
    public function get_brands(Request $request) {
        $search['content'] = $request->input('search');

        $qt = intval($request->input('qt', 10));
        $users = Brand::where('name', 'like', '%'.$search['content'].'%')
            ->orderByDesc('id')
            ->paginate($qt);

        echo json_encode($users);
        return;
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

        echo json_encode($create);
        return;
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

        echo json_encode($delete);
        return;
    }

    protected function encodeImg($n, $ext, $ql, $w = 300, $h = 300) {
        $image = Image::make($n)->encode($ext, $ql);
        $image->resize($w, $h);
        $img_name = md5(strtotime('now').rand(1000,9999)).'.'.$ext;
        $image->save(public_path('media').'/brands/'.$img_name);
        return $img_name;
    }
}
