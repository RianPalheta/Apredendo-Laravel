<?php

namespace App\Http\Handlers;

use App\Models\User;

class UserHandler {

    static public function getUsers() {
        $users = [];
        $users = User::all();
        return $users;
    }
}
