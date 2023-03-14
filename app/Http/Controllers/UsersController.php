<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public $user;
    public function __construct(User $request)
    {
        $this->user = $request;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = $this->user->where('role', 'client')->get();;
        if ($usuarios == null)
            return response()->json(array('status' => 'Não existem Usuários!'), 404);
        return response()->json(array('usuarios' => $usuarios), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = $this->user->rules();
        $feedback = $this->user->feedback();

        $request->validate($rules, $feedback);

        if ($usuario = $this->user->create($request->all()))
            return response()->json(['status' => 'Usuário cadastrado com sucesso!', 'usuarios' => $usuario], 200);
        else
            return response()->json(['status' => 'Erro ao cadastrar usuário'], 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $usuario = $this->user->find($id);
        if ($usuario == null)
            return response()->json(['status' => 'Usuário não cadastrado!'], 404);
        return response()->json(['usuario' => $usuario], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $usuario = $this->user->find($id);

        if ($usuario == null) {
            return response()->json(['status' => 'Usuário não cadastrado'], 404);
        }

        if ($request->method() == 'PATCH') {
            $regrasDinamicas = array();

            foreach ($usuario->regras() as $campo => $regra) {
                if (array_key_exists($campo, $request->all())) {
                    $regrasDinamicas[$campo] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $usuario->feedback());
        } else {
            $request->validate($usuario->regras(), $usuario->feedback());
        }

        $usuario->update($request->all());
        return response()->json(['status' => 'Usuário atualizado com sucesso!', 'usuario' => $usuario], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $usuario = $this->user->find($id);

        if ($usuario == null) {
            return response()->json(['status' => 'Usuário não cadastrado'], 404);
        } else {
            $usuario->delete();
            return response()->json(['status' => 'Usuário removido com successo!', 'usuario' => $usuario], 200);
        }
    }

    public function search($searchTerm)
    {
        $query = $this->user->query();

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%$searchTerm%")
                    ->orWhere('email', 'like', "%$searchTerm%");
            });
        }

        $usuarios = $query->get();

        return response()->json(['usuarios' => $usuarios], 200);
    }
}
