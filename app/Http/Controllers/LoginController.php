<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status' => 'Email ou senha incorretos!'], 401);
        }

        $token = $user->createToken('app-token', ['role' => $user->role]);
        return response()->json([
            'access_token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'expires_in' => config('sanctum.expiration'),
            'role' => $user->role,
            'user' => $user
        ]);
        
    }

    public function register(Request $request)
    {
        $request->merge([
            'password' => bcrypt($request->password)
        ]);
        $user = User::create($request->all());

        return [
            'status' => 'Usuário cadastrado com sucesso!',
            'usuario'    => $user
        ];
    }

    public function me(Request $request)
    {
        $request->user()->role == 'admin' ? $request->user()->role = 'Administrador' : '';
        $request->user()->role == 'client' ? $request->user()->role = 'Cliente' : '';

        return [
            'Id' => $request->user()->id,
            'Nome' => $request->user()->name,
            'Perfil' => $request->user()->role
        ];
    }

    public function updateEmail(Request $request)
    {
        /** @var User */
        $user = $request->user();
        $user->email = $request->email;
        $user->save();

        return [
            'message' => 'User e-mail updated successfully!',
            'user'    => $user
        ];
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return [
            'message' => 'All user tokens were revoked !',
        ];
    }
}
