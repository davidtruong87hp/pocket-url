<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function __invoke()
    {
        return response()->json(['user' => request()->user()], 200);
    }
}
