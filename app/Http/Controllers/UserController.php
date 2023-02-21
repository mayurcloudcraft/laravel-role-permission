<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        return response()->json([
            'status' => true,
            'msg' => 'User profile.',
            'data' => $request->user()
        ]);
    }

    public function list()
    {
        $users = User::latest('id', 'desc')->paginate(10);

        return response()->json([
            'status' => true,
            'msg' => 'User listing',
            'data' => $users
        ]);
    }

    public function show(User $user)
    {
        return response()->json([
            'status' => true,
            'msg' => 'User view.',
            'data' => $user
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required','confirmed','min:8'],
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'msg' => $validator->errors()->first(),
                'data' => []
            ]);
        }

        $user = User::create([
            ...$request->only('name','email'),
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'status' => true,
            'msg' => 'User created.',
            'data' => $user
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => [
                'required',
                Rule::unique('users')->whereNot('id', $user->id)
            ],
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'msg' => $validator->errors()->first(),
                'data' => []
            ]);
        }

        $user->fill($request->only('name','email'))->save();

        return response()->json([
            'status' => true,
            'msg' => 'User updated.',
            'data' => $user
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'status' => true,
            'msg' => 'User deleted.',
            'data' => []
        ]);
    }

    public function getRoles(Request $request)
    {
        $roles = $request->user()->roles->makeHidden('pivot');

        return response()->json([
            'status' => true,
            'msg' => 'User roles.',
            'data' => $roles
        ]);
    }


}
