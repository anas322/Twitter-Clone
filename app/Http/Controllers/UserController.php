<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return new UserResource($request->user());
    }

    public function search(Request $request)
    {
        $users = User::where('name', 'LIKE', "%$request->search%")->limit(10)->get();

        return response()->json([
            'users' => UserResource::collection($users)
        ]);
    }
}
