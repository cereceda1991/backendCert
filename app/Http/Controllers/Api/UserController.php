<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $perPage = 50; // Número de usuarios por página
        $users = User::paginate($perPage);
    
        $response = [
            'status' => 'success',
            'message' => 'Users found!',
            'data' => [
                'users' => $users->items(),
                'currentPage' => $users->currentPage(),
                'perPage' => $users->perPage(),
                'totalPages' => $users->lastPage(),
                'totalCount' => $users->total(),
            ],
        ];
    
        return response()->json($response, Response::HTTP_OK);
    }
      
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->success($user , 'User found!');

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'address' => 'nullable',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }
    
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->address = 'default';
        $user->save();
    
        $token = JWTAuth::fromUser($user);
    
        return response()->success(['token' => $token,'user' => $user ], 'User successfully registered!');

    }
 
    public function update(Request $request, $id)
    {
            $user = User::findOrFail($id);
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $id,
                'password' => 'required|min:6',
                'address' => 'nullable|string',
            ]);
        
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->address = $request->address; 
            $user->save();
        
            return response()->success($user,'User has been successfully updated');        
    }    
  
    public function destroy($id)
    {
        User::destroy($id);
        return response()->json(['message' => "Deleted"], Response::HTTP_OK);
    }
}
