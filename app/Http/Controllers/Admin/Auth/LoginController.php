<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::ADMIN;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index() {
        return view('admin.login');
    }

    public function authenticate(Request $request) {
        $data = $request->only([
            'email',
            'password',
        ]);

        $remember = $request->input('remember', false);

        $validator = $this->validator($data);
        if($validator->fails()) {
            $login['success'] = false;
            $login['message'] = $validator->errors(); // 'Dados inválidos, tente novamente.'
            echo json_encode($login);
            return;
        }

        if(Auth::attempt($data, $remember)) {
            $login['success'] = true;
            $login['message'] = '';
            echo json_encode($login);
            return;
        } else {
            $login['success'] = false;
            $login['message'] = 'Email e/ou senha inválidos.';
            echo json_encode($login);
            return;
        }
    }
    public function logout() {
        Auth::logout();
        return redirect()->route('login');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:100'],
            'password' => ['required', 'string', 'min:4'],
        ]);
    }
}
