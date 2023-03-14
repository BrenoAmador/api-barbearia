<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public $administrador;
    public function __construct(User $request)
    {
        $this->administrador = $request;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $administradores = $this->administrador->where('role', 'admin')->get();
        if ($administradores == '' )
            return response()->json(array('status' => 'Não existem Administradores!'),404);
        return response()->json(array('administradores' => $administradores ),200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $rules = $this->administrador->rules();
        $feedback = $this->administrador->feedback();

        $request->validate($rules, $feedback);

        if ($usuario = $this->administrador->create($request->all()))
            return response()->json(['status' => 'Administrador cadastrado com sucesso!', 'administradores' => $usuario], 200);
        else
            return response()->json(['status' => 'Erro ao cadastrar administrador'], 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $usuario = $this->administrador->find($id);
        if ($usuario == null)
            return response()->json(['status' => 'Administrador não cadastrado!'], 404);
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
        $usuario = $this->administrador->find($id);

        if ($usuario == null) {
            return response()->json(['status' => 'Administrador não cadastrado'], 404);
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
        return response()->json(['status' => 'Administrador atualizado com sucesso!', 'usuario' => $usuario], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $usuario = $this->administrador->find($id);

        if ($usuario == null) {
            return response()->json(['status' => 'Administrador não cadastrado'], 404);
        } else {
            $usuario->delete();
            return response()->json(['status' => 'Administrador removido com successo!', 'usuario' => $usuario], 200);
        }
    }

    public function administradorname()
    {
        return "Administrador logado!";
    }
}
