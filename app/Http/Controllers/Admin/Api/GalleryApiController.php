<?php

namespace App\Http\Controllers\Admin\Api;

use App\Models\Photo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class GalleryApiController extends Controller
{
    public function get_photos(Request $request) {
        $search['content'] = $request->input('search');

        $qt = intval($request->input('qt', 20));
        $photos = Photo::where('name', 'like', '%'.$search['content'].'%')
            ->orderByDesc('id')
            ->paginate($qt);

        echo json_encode($photos);
        return;
    }

    public function store(Request $request)
    {
        $photos = $request->allFiles()['photo'];

        for($i = 0; $i < count($photos); $i++) {
            $photo = $photos[$i];
            $validator = Validator::make($photos, [
                $i => 'image|max:5243|dimensions:max_width=4000,max_height=3000' // 5243
            ]);
            if($validator->fails()) {
                $create['success'] = false;
                $create['message'] = $validator->errors();
            } else {
                $date_photo = $this->encodeImg($photo->path(), 'webp', 100);
                $data = new Photo;
                $data->name = explode('.', $photo->getClientOriginalName())[0];
                $data->hash = $date_photo['hash'];
                $data->size = $date_photo['size'];
                $data->dimension = $date_photo['dimension'];
                $data->save();

                $create['success'] = true;
            }
            unset($photos[$i]);
            unset($photo);
        }
        echo json_encode($create);
        return;
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $photo = Photo::find($id);
        $data = $request->only([
            'name',
            'description'
        ]);

        if($photo) {
            $validator = Validator::make($data, [
                'name'  => 'string|max:100',
                'description'  => 'max:100',
            ]);

            if($validator->fails()) {
                $update['success'] = false;
                $update['message'] = $validator->errors();
            } else {
                $update['success'] = true;
                $update['message'] = '';

                foreach($data as $key => $value) {
                    $photo->$key = $value;
                }
                $photo->save();
            }
        } else {
            $update['success'] = false;
            $update['message'] = [
                'not_photo' => 'Foto não encontrada'
            ];
        }

        echo json_encode($update);
        return;
    }

    public function destroy($id)
    {
        $photo = Photo::find($id);
        if($photo->hash != 'default.png') {
            @unlink(
                public_path('media')
                .'/gallery/'
                .$photo->hash
            );
        }
        $photo->delete();
        $delete['success'] = true;

        echo json_encode($delete);
        return;
    }

    private function encodeImg($n, $ext, $ql) {
        $img = [];
        $image = Image::make($n);
        if(in_array($image->mime(), ['image/jpg', 'image/jpeg', 'image/png'])) {
            $image->encode($ext, $ql);
        }
        $img_hash = md5(strtotime('now').rand(1000,9999)).'.'.$ext;
        $image->save(public_path('media').'/gallery/'.$img_hash);
        $img['hash'] = $img_hash;
        $img['size'] = $image->filesize();
        $img['dimension'] = $image->width().' x '.$image->height();
        return $img;
    }
}
