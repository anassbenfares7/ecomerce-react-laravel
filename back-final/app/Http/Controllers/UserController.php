<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // DB::table('users')->truncate();
        // User::create(["name"=> "anass", "email"=> "anass@gmail.com","password"=> Hash::make("anass12345"), "role"=> 1]);
        // User::create(["name"=> "ilyass", "email"=> "ilyass@gmail.com","password"=> Hash::make("ilyass12345"), "role"=> 1]);

        $users = User::orderBy('created_at', 'desc')->where('role', 0)->get();
        $admins = User::where('role', 1)->get();

        return response()->json(["users" => $users, "admins" => $admins]);
    }

    public function store(Request $request)
    {
        try {
            User::create($request->all());
            return response()->json(['message' => 'User created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id){
        $user = User::find($id);
        return response()->json($user);
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
