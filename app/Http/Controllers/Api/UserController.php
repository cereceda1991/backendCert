<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json([
            "result" => $users
        ], Response::HTTP_OK);
    }

    public function show($id)
    {
    $user = User::findOrFail($id);
    return response()->json(['result' => $user], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'lastname'=>'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(['result' => $user], Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'lastname'=>'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'required|min:6',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(['result' => $user], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        User::destroy($id);
        return response()->json(['message' => "Deleted"], Response::HTTP_OK);
    }
}
