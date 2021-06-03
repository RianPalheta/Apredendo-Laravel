<?php

namespace App\Http\Controllers\Admin\Api;

use App\Models\Photo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

        return response()->json($photos);
    }

    public function store(Request $request)
    {
        $photos = $request->allFiles()['photo'];

        for($i = 0; $i < count($photos); $i++) {
            $photo = $photos[$i];
            $validator = Validator::make($photos, [
                $i => 'image|max:2000|dimensions:max_width=4000,max_height=3000'
            ]);
            if($validator->fails()) {
                $create['success'] = false;
                $create['message'] = $validator->errors();
            } else {
                $data_photo = $this->encodeImg($photo->path(), 'webp', 100);
                $data = new Photo;
                $data->name = explode('.', $photo->getClientOriginalName())[0];
                $data->hash = $data_photo['hash'];
                $data->size = $data_photo['size'];
                $data->dimension = $data_photo['dimension'];
                $data->save();

                $create['success'] = true;
            }
            unset($photo);
        }

        return response()->json($create);
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

    private function encodeImg($n, $ext, $ql, $w = 300, $h = 300) {
        $img_hash = md5(strtotime('now').rand(1000,9999)).'.'.$ext;

        $image = Image::make($n);

        if(in_array($image->mime(), ['image/jpg', 'image/jpeg', 'image/png', 'image/svg'])) {
            $image->encode($ext, $ql);
        } else {
            $ext = explode('/', $image->mime())[1];
        }

        if($image->width() > 1000 || $image->height() > 1000) $image->resize($w, $h);

        $image->save(public_path('media').'/gallery/'.$img_hash, $ql);

        $img['hash'] = $img_hash;
        $img['size'] = $image->filesize();
        $img['dimension'] = $image->width().' x '.$image->height();

        return $img;
    }
}
