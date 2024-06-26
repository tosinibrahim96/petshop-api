<?php

declare(strict_types=1);

namespace App\Http\Controllers\ShopUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $token = auth()->attempt(['email' => 'kautzer.ramona@example.net', 'password' => 'userpassword']);
        return response()->json(['token' => $token]);
    }

    public function protected(Request $request)
    {
        return response()->json(['message' => "This is a protected route"]);
    }
}
