<?php

namespace App\Http\Controllers\Admin\Api;

use App\Models\Categorie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class CategoryApiController extends Controller
{
    public function get_categories(Request $request)
    {
        $search['content'] = $request->input('search');

        $qt = intval($request->input('qt', 10));
        $categories = Categorie::where('name', 'like', '%'.$search['content'].'%')
            ->orderByDesc('id')
            ->paginate($qt);

        $json = json_encode($categories);
        $array = json_decode($json, true);
        $data = $array['data'];
        unset($array['data']);

        $pre = [];
        foreach($data as $item) {
            $item['subs'] = [];
            $pre[$item['id']] = $item;
        }
        while($this->stillNeed($pre)) {
            $this->organizeCategory($pre);
        }
        $array['data'] = $pre;

        echo json_encode($array);
        return;
    }

    public function store(Request $request)
    {
        $data = $request->only([
            'name',
            'sub',
            'img'
        ]);

            
        if( $request->hasFile('img') &&
            $request->file('img')->isValid()) {
            $data['img'] = $request->file('img');
        }

        if(!empty($data['sub'])) {
            $data['sub'] = intval($data['sub']);
        } elseif(isset($data['sub'])) {
            $data['sub'] = null;
        }

        $validator = Validator::make($data, [
            // 'sub'   => 'string',
            'img'   => 'image|max:5243',
            'name'  => 'required|string|max:100,unique:categories'
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

            $category = new Categorie;
            foreach($data as $key => $value) {
                $category->$key = $value;
            }
            $category->save();
        }

        echo json_encode($create);
        return;
    }

    public function update(Request $request, $id)
    {   
        $cat = Categorie::find($id);

        if($cat) {
            $inputs = $request->only([
                'name',
                'sub'
            ]);
    
            foreach ($inputs as $key => $item) {
                !empty($inputs[$key]) ? $data[$key] = $item : '';
            }
                
            if( $request->hasFile('img') &&
                $request->file('img')->isValid()) {
                $data['img'] = $request->file('img');
            }
    
            $validator = Validator::make($data, [
                // 'sub'   => 'string',
                'img'   => 'image|max:5243',
                'name'  => 'required|string|max:100,unique:categories'
            ]);
            if($validator->fails()) {
                $update['success'] = false;
                $update['message'] = $validator->errors();
            } else {
                $update['success'] = true;
                $update['message'] = '';
    
                if(!empty($data['img'])) {
                    if($data['img'] != $cat->img) {
                        $data['img'] = $this->encodeImg(
                            $request
                            ->file('img')
                            ->path(),
                            'webp',
                            100
                        );
                    }
                }

                if(!empty($data['sub'])) {
                    $data['sub'] = intval($data['sub']);
                } elseif($data['sub'] == 0) {
                    $data['sub'] = null;
                }

                foreach($data as $key => $value) {
                    $cat->$key = $value;
                }
                $cat->save();
            }
    
            echo json_encode($update);
            return;
        }
    }

    public function destroy($id)
    {
        $categories = $this->scanCategory($id);
        if($this->hasProduct($categories) == false) {
            $this->deleteCategories($categories);
            
            $delete['success'] = true;
            echo json_encode($delete);
            return;
        } 
        $delete['success'] = false;
        $delete['message'] = 'Há produtos cadastrados nessa categoria.';
        echo json_encode($delete);
        return;
    }

    private function organizeCategory(&$array = []) {
        foreach($array as $id => $item) {
            if(!empty($item['sub'])) {
                $array[$item['sub']]['subs'][$item['id']] = $item;
                unset($array[$id]);
                break;
            }
        }
    }

    private function stillNeed($array = []) {
        foreach($array as $item) {
            if(!empty($item['sub'])) {
                return true;
            }
        }
        return false;
    }

    private function scanCategory($id, $cats = []) {
        if(!in_array($id, $cats)) $cats[] = $id;
        
        $category = Categorie::select('id')
        ->where('sub', $id)
        ->get();
        $category = json_encode($category);
        $data = json_decode($category, true);
        
        if(count($data) > 0) {
            foreach($data as $item) {
                if(!in_array($item['id'], $cats)) {
                    $cats[] = $item['id'];
                }
                
                $cats = $this->scanCategory($item['id'], $cats);
            }
        }
        
        return $cats;
    }

    private function hasProduct($array = []) {
        
        $product = DB::select("SELECT COUNT(*) as c FROM products WHERE id_category IN (".implode(',', $array).")");
        $product = json_encode($product);
        $product = json_decode($product, true);

        if(intval($product[0]['c']) > 0) {
            return true;
        } else {
            return false;
        }
    }

    private function deleteCategories($array) {
        $cat = DB::select("SELECT img FROM categories WHERE id IN (".implode(',', $array).")");
        $cat = json_encode($cat);
        $cat = json_decode($cat, true);

        foreach ($cat as $item) {
            if($item['img'] != 'default.png') {
                @unlink(
                    public_path('media')
                    .'/categories/'
                    .$item['img']
                );
            }
        }

        DB::delete("DELETE FROM categories WHERE id IN (".implode(',', $array).")");
    }
    
    protected function encodeImg($n, $ext, $ql, $w = 300, $h = 300) {
        $image = Image::make($n)->encode($ext, $ql);
        $image->resize($w, $h);
        $img_name = md5(strtotime('now').rand(1000,9999)).'.'.$ext;
        $image->save(public_path('media').'/categories/'.$img_name);
        return $img_name;
    }
}
