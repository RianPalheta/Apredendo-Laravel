<?php

namespace App\Http\Controllers\Admin;

use App\Models\Option;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.options.index');
    }

    public function get_options(Request $request) {
        $view = $request->input('view', false);
        $search['content'] = $request->input('search');
        $qt = intval($request->input('qt', 10));
        $options = Option::where('name', 'like', '%'.$search['content'].'%')
            ->orderByDesc('id')
            ->paginate($qt);
        if(!$view)
            return response()->json($options);
        else
            return view('admin.options.select', [
                'options' => $options
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
            'name'  => 'required|string|max:100,unique:options'
        ]);
        if($validator->fails()) {
            $create['success'] = false;
            $create['message'] = $validator->errors();
        } else {
            $create['success'] = true;
            $create['message'] = '';

            $option = new Option;
            foreach($data as $key => $value) {
                $option->$key = $value;
            }
            $option->save();
        }

        return response()->json($create);
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
        $option = Option::find($id);
        $data = $request->only(['name']);

        if($option) {
            $validator = Validator::make($data, [
                'name'  => 'string|nullable|max:100|unique:options'
            ]);
            if($validator->fails()) {
                $update['success'] = false;
                $update['message'] = $validator->errors();
            } else {
                $update['success'] = true;
                $update['message'] = '';

                foreach($data as $key => $value) {
                    $option->$key = $value;
                }
                $option->save();
            }
        } else {
            $update['success'] = false;
            $update['message'] = [
                'not_brand' => 'Opção não encontrada'
            ];
        }

        return response()->json($update);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->total_products($id) > 0) {
            $delete = [
                'success' => false,
                'message' => 'Existem produtos cadastrados com essa opção.'
            ];
        } else {
            $delete['success'] = true;
            $brand = Option::find($id);
            $brand->delete();
        }

        return response()->json($delete);
    }

    private function total_products($id) {
        return DB::table('products_options')->select('id_option')->where('id_option', $id)->count();
    }
}
