<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class UserApiController extends Controller
{
    public function get_users(Request $request) {
        $search['content'] = $request->input('search');
        switch($search['content']) {
            case is_numeric($search['content']):
                $search['type'] = 'id';
            break;
            case filter_var($search['content'], FILTER_VALIDATE_EMAIL):
                $search['type'] = 'email';
            break;
            case is_string($search['content']):
                $search['type'] = 'name';
            break;
        }

        // echo $search['type'].' '.$search['content'];

        $qt = intval($request->input('qt', 10));
        $users = User::where($search['type'], 'like', '%'.$search['content'].'%')
            ->orderByDesc('id')
            ->paginate($qt);

        echo json_encode($users);
        return;
    }

    public function show($id)
    {
        //
    }

    public function create_user(Request $request, $id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $data = [];
        $user = User::find($id);

        if( $request->hasFile('avatar') &&
            $request->file('avatar')->isValid()) {
            $data['avatar'] = $request->file('avatar');

            if($user->avatar != 'default.png') {
                @unlink(public_path('media').'/users/'.$user->avatar);
            }
        }

        if( !empty($request->input('email')) &&
            $user->email != $request->input('email')) {
            $data['email'] = $request->input('email');
        }

        if(!empty($request->input('birthday'))) {
            $d = explode('/', $request->input('birthday'));
            if(count($d) == 3) {
                $date = $d[2].'-'.$d[1].'-'.$d[0];
                if(strtotime($date) >= strtotime(date('Y-m-d'))) {
                    $update['success'] = false;
                    $update['message'] = [
                        'birthday' => ['Data de nascimento inválida.']
                    ];
                    echo json_encode($update);
                    return;
                } else {
                    $data['birthday'] = $date;
                }
            }
        }

        !empty($request->input('uf'))                       ? $data['uf'] = $request->input('uf') : '';
        !empty($request->input('cep'))                      ? $data['cep'] = $request->input('cep') : '';
        !empty($request->input('cpf'))                      ? $data['cpf'] = $request->input('cpf') : '';
        !empty($request->input('city'))                     ? $data['city'] = $request->input('city') : '';
        !empty($request->input('road'))                     ? $data['road'] = $request->input('road') : '';
        !empty($request->input('name'))                     ? $data['name'] = $request->input('name') : '';
        !empty($request->input('password'))                 ? $data['password'] = $request->input('password') : '';
        !empty($request->input('district'))                 ? $data['district'] = $request->input('district') : '';
        !empty($request->input('telephone'))                ? $data['telephone'] = $request->input('telephone') : '';
        !empty($request->input('complement'))               ? $data['complement'] = $request->input('complement') : '';
        !empty($request->input('number_home'))              ? $data['number_home'] = $request->input('number_home') : '';
        !empty($request->input('password_confirmation'))    ? $data['password_confirmation'] = $request->input('password_confirmation') : '';

        $validator = $this->validator($data);
        if($validator->fails()) {
            $update['success'] = false;
            $update['message'] = $validator->errors();
        } else {
            $update['success'] = true;
            $update['message'] = '';

            if(!empty($data['avatar'])) {
                $data['avatar'] = $this->avatar_user(
                    $request
                    ->file('avatar')
                    ->path(),
                    'webp',
                    100
                );
                $update['avatar'] = $data['avatar'];
            }

            !empty($request->input('password'))
                ? $data['password'] = Hash::make($data['password'])
                : '';
            unset($data['password_confirmation']);

            foreach($data as $key => $value) {
                $user->$key = $data[$key];
                $user->save();
            }
        }

        echo json_encode($update);
        return;
    }

    public function destroy($id)
    {
        $loggedId = intval(Auth::id());

        if($loggedId !== intval($id)) {
            $user = User::find($id);
            @unlink(
                public_path('media')
                .'/users/'
                .$user->avatar
            );
            $user->delete();
            $delete['success'] = true;
        } else {
            $delete['success'] = false;
            $delete['msg'] = 'Você não pode apagar esse usuário';
        }

        echo json_encode($delete);
        return;
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'uf'            => 'min:2',
            'cpf'           => 'cpf_cnpj',
            'cep'           => 'min:8|max:10',
            'telephone'     => 'string|max:16',
            'complement'    => 'string|max:100',
            'name'          => 'string|max:100',
            'city'          => 'string|max:100',
            'district'      => 'string|max:100',
            'number_home'   => 'string|max:100',
            'avatar'        => 'image|max:2024000',
            'password'      => 'string|min:4|max:100|confirmed',
            'email'         => 'string|email|max:100|unique:users',
        ]);
    }

    protected function avatar_user($n, $ext, $ql, $w = 300, $h = 300) {
        $image = Image::make($n)->encode($ext, $ql);
        $image->resize($w, $h);
        $img_name = md5(strtotime('now').rand(1000,9999)).'.'.$ext;
        $image->save(public_path('media').'/users/'.$img_name);
        return $img_name;
    }
}
