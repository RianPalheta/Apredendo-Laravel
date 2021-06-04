<?php

namespace App\Http\Controllers\Admin\Api;

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
            case filter_var($search['content'], FILTER_VALIDATE_EMAIL):
                $search['type'] = 'email';
            break;
            case is_string($search['content']):
                $search['type'] = 'name';
            break;
        }

        $qt = intval($request->input('qt', 10));
        $users = User::where($search['type'], 'like', '%'.$search['content'].'%')
            ->orderByDesc('id')
            ->paginate($qt);

        return response()->json($users);
    }

    public function show($id) {
        //
    }

    public function store(Request $request) {
        $inputs = $request->only([
            'name',
            'email',
            'telephone',
            'birthday',
            'uf',
            'cep',
            'cpf',
            'city',
            'road',
            'district',
            'password',
            'complement',
            'number_home',
            'password_confirmation'

        ]);

        foreach($inputs as $key => $v) {
            !empty($inputs[$key]) ? $data[$key] = $v : '';
        }

        if( $request->hasFile('avatar') &&
            $request->file('avatar')->isValid()) {
            $data['avatar'] = $request->file('avatar');
        }
        if(!empty($data['birthday'])) {
            $d = explode('/', $request->input('birthday'));
            if(count($d) == 3) {
                $date = $d[2].'-'.$d[1].'-'.$d[0];
                if(strtotime($date) >= strtotime(date('Y-m-d'))) {
                    $create['success'] = false;
                    $create['message'] = [
                        'birthday' => ['Data de nascimento inválida.']
                    ];
                    echo json_encode($create);
                    return;
                } else {
                    $data['birthday'] = $date;
                }
            }
        }

        $validator = $this->validator($data, true);
        if($validator->fails()) {
            $create['success'] = false;
            $create['message'] = $validator->errors();
        } else {
            $create['success'] = true;
            $create['message'] = '';

            if(!empty($data['avatar'])) {
                $data['avatar'] = $this->avatar_user(
                    $request
                    ->file('avatar')
                    ->path(),
                    'webp',
                    100
                );
            }

            !empty($data['password'])
                ? $data['password'] = Hash::make($data['password'])
                : '';

            unset($data['password_confirmation']);

            $user = new User;
            foreach($data as $key => $value) {
                $user->$key = $value;
            }
            $user->save();
        }

        echo json_encode($create);
        return;
    }

    public function update(Request $request, $id) {
        $user = User::find($id);
        if(!$user) {
            $update['success'] = false;
            $update['message'] = [
                'not_allowed' => 'Você não tem permissão para realizar essa tarefa.'
            ];
        }

        $inputs = $request->only([
            'name',
            'telephone',
            'birthday',
            'uf',
            'cep',
            'cpf',
            'road',
            'city',
            'district',
            'password',
            'complement',
            'number_home',
            'password_confirmation'

        ]);

        foreach($inputs as $key => $v) {
            !empty($inputs[$key]) ? $data[$key] = $v : '';
        }

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

            $user->update($data);
        }

        echo json_encode($update);
        return;
    }

    public function destroy($id) {
        $loggedId = intval(Auth::id());

        if($loggedId !== intval($id)) {
            $user = User::find($id);
            if($user->avatar != 'default.png') {
                @unlink(
                    public_path('media')
                    .'/users/'
                    .$user->avatar
                );
            }
            $user->delete();
            $delete['success'] = true;
        } else {
            $delete['success'] = false;
            $delete['msg'] = 'Você não pode apagar esse usuário';
        }

        echo json_encode($delete);
        return;
    }

    protected function validator(array $data, $req = false) {
        return Validator::make($data, [
            'uf'            => 'min:2',
            'avatar'        => 'image|max:5243',
            'complement'    => 'string|max:100',
            'city'          => 'string|max:100',
            'district'      => 'string|max:100',
            'number_home'   => 'string|max:100',
            'road'          => 'string|max:100',
            'cep'           => 'string|min:8|max:10',
            'cpf'           => $req ? 'required|cpf_cnpj' : 'cpf_cnpj',
            'birthday'      => $req ? 'required|min:8|max:10' : 'min:8|max:10',
            'telephone'     => $req ? 'required|string|max:16' : 'string|max:16',
            'name'          => $req ? 'required|string|max:100' : 'string|max:100',
            'password'      => $req ? 'required|string|min:4|max:100|confirmed' : 'string|min:4|max:100|confirmed',
            'email'         => $req ? 'required|string|email|max:100|unique:users' : 'string|email|max:100|unique:users',
        ]);
    }

    protected function avatar_user($n, $ext, $ql, $w = 300, $h = 300) {
        $image = Image::make($n)->encode($ext, $ql);
        $image->resize($w, $h);
        $imgName = md5(strtotime('now').rand(1000,9999)).'.'.$ext;
        $image->save(public_path('media').'/users/'.$imgName);
        return $imgName;
    }
}
