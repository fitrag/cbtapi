<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function index(){
        $users = User::paginate(5);
        return new UserResource(true, 'List data user', $users);
    }
}
