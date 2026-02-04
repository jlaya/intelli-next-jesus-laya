<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    // Listar todos los usuarios (findAll)
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    // Crear un usuario (create)
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Password Argon2
        $data['password'] = Hash::make($request->password);

        $user = User::create($data);
        return response()->json($user, 201);
    }

    // Mostrar un usuario específico (findOne)
    public function show(User $user)
    {
        return response()->json($user, 200);
    }

    // Actualizar usuario (update)
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'string',
        ]);

        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return response()->json($user, 200);
    }

    // Eliminar usuario (delete)
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted'], 204);
    }
}
