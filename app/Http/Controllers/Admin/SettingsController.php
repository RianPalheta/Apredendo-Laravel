<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = [];
        $dbsettings = Setting::get();
        foreach ($dbsettings as $item)
            $settings[$item['name']] = $item['content'];
        return view('admin.Settings.index', [
            'settings' => $settings
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->only([
            'uf',
            'cep',
            'city',
            'road',
            'n-shop',
            'bg-site',
            'bg-text',
            'country',
            'district',
            'url-site',
            'low-stock',
            'logo-site',
            'icon-site',
            'name-shop',
            'email-admin',
            'maintenance',
            'email-sender',
            'type-company',
            'key-words-site',
            'allow-purchases',
            'description-site',
        ]);
        $validator = $this->validator($data);
        if($validator->fails()) {
            $update['success'] = false;
            $update['message'] = $validator->errors();
        } else {
            $update['success'] = true;
            $update['message'] = '';

            empty($data['low-stock']) ? $data['low-stock'] = false : $data['low-stock'] = true;
            empty($data['maintenance']) ? $data['maintenance'] = false : $data['maintenance'] = true;
            empty($data['allow-purchases']) ? $data['allow-purchases'] = false : $data['allow-purchases'] = true;

            foreach($data as $i => $v) {
                Setting::where('name', $i)->update([
                    'content' => $v
                ]);
            }
        }
        return response()->json($update);
    }

    protected function validator(array $data) {
        return Validator::make($data, [
            'uf'                => 'string|nullable|max:100',
            'cep'               => 'string|nullable|max:100',
            'road'              => 'string|nullable|max:100',
            'city'              => 'string|nullable|max:100',
            'n-shop'            => 'string|nullable|max:100',
            'country'           => 'string|nullable|max:100',
            'district'          => 'string|nullable|max:100',
            'url-site'          => 'string|nullable|max:100',
            'name-shop'         => 'string|nullable|max:100',
            'low-stock'         => 'string|nullable|max:5',
            'email-admin'       => 'string|nullable|max:100',
            'maintenance'       => 'string|nullable|max:5',
            'email-sender'      => 'string|nullable|max:100',
            'type-company'      => 'string|nullable|max:100',
            'allow-purchases'   => 'string|nullable|max:5',
            'key-words-site'    => 'string|nullable|max:100',
            'description-site'  => 'string|nullable|max:100',
        ]);
    }
}
