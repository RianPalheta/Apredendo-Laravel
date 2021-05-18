<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.Users.index', ['logged' => Auth::id()]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.Users.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function edit($id)
    {
        $this->middleware('auth');

        $user = User::find($id);
        if($user) {
            if(!empty($user->birthday)) {
                $date = explode('-', $user->birthday);
                $user->birthday = $date[2].$date[1].$date[0];
            }
            return view('admin.Users.edit', [
                'user' => $user
            ]);
        }
        return redirect()->route('users.index');
    }
}
