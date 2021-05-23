<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use Illuminate\Http\Request;

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
        //
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
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
}
