<?php

namespace App\Http\Controllers\Admin\Api;

use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class PageApiController extends Controller
{
    public function get_pages(Request $request) {
        $search['content'] = $request->input('search');

        $qt = intval($request->input('qt', 10));
        $users = Page::where('title', 'like', '%'.$search['content'].'%')
            ->orderByDesc('id')
            ->paginate($qt);

        echo json_encode($users);
        return;
    }

    public function create_page(Request $request)
    {
        $data = $request->only([
            'title',
            'body'
        ]);
        $data['slug'] = Str::slug($data['title'], '-');

        $validator = $this->validator($data);
        if($validator->fails()) {
            $create['success'] = false;
            $create['message'] = $validator->errors();
        } else {
            $create['success'] = true;
            $create['message'] = '';

            $page = new Page;
            foreach($data as $key => $value) {
                $page->$key = $value;
            }
            $page->save();
        }

        echo json_encode($create);
        return;
    }

    public function update(Request $request, $id)
    {
        $page = Page::find($id);

        if($page) {
            $inputs = $request->only([
                'title',
                'body'
            ]);
            foreach($inputs as $key => $v) {
                !empty($inputs[$key]) ? $data[$key] = $v : '';
            }

            if($data['title'] != $page->title) {
                $data['slug'] = Str::slug($data['title'], '-');
            }

            $validator = $this->validator($data);
            if($validator->fails()) {
                $update['success'] = false;
                $update['message'] = $validator->errors();
            } else {
                $update['success'] = true;
                $update['message'] = '';

                foreach($data as $key => $value) {
                    $page->$key = $value;
                }
                $page->save();
            }

            echo json_encode($update);
            return;
        }

        $update['success'] = false;
        $update['message'] = [
            'not_found' => 'Página não encontrada'
        ];

        echo json_encode($update);
        return;
    }

    public function imageupload(Request $request) {
        $data['file'] = $request->file('file');
        $validator = Validator::make($data, [
            'file'  => 'image|mimes:jpeg,jpg,png,gif,icon,webp|max:5243'
        ]);
        if($validator->fails()) {
            $upload['success'] = false;
            $upload['message'] = $validator->errors();
            echo json_encode($upload);
            return;
        }

        $img = $this->encodeImg($data['file']->path(), 'webp', 100);
        return [
            'location' => asset('media/pages/'.$img)
        ];
    }

    public function destroy($id)
    {
        $page = Page::find($id);
        $page->delete();

        $delete['success'] = true;

        echo json_encode($delete);
        return;
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'title' => 'required|string|max:100',
            'slug'  =>  'string|max:100|unique:pages',
        ]);
    }

    protected function encodeImg($n, $ext, $ql) {
        $image = Image::make($n)->encode($ext, $ql);
        $img_name = md5(strtotime('now').rand(1000,9999)).'.'.$ext;
        $image->save(public_path('media').'/pages/'.$img_name);
        return $img_name;
    }
}
