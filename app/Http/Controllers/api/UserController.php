<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use app\Models\User;

class UserController extends Controller
{
    public function search(String $query)
    {
        $users = User::where('email', 'like', '%' . $query . '%')
        ->get();
        return response()->json($users);
    }
}
