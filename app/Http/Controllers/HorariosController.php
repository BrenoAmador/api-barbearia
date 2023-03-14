<?php

namespace App\Http\Controllers;

use App\Models\Horarios;
use Illuminate\Http\Request;

class HorariosController extends Controller
{
    private $horarios;
    public function __construct(Horarios $request)
    {
        $this->horarios = $request;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $horarios = $this->horarios->all();
        if ($horarios == null)
            return response()->json(['status' => 'Não existem horários!']);
        else
            return response()->json(['horarios' => $horarios]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = $this->horarios->rules();
        $feedback = $this->horarios->feedback();
        $request->validate($rules, $feedback);

        if ($horario = $this->horarios->create($request->all()))
            return response()->json(['status' => 'Horário marcado com sucesso!', 'horario' => $horario]);
        else
            return response()->json(['status' => 'Erro ao marcar horário!']);
    }
    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $horario = $this->horarios->with('usuario')->find($id);
        if ($horario == null)
            return response()->json(['status' => 'Horário não marcado!'], 404);
        return response()->json(['horario' => $horario]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $horario = $this->horarios->find($id);

        if ($horario == null) {
            return response()->json(['status' => 'Horário não encontrado!'], 404);
        }

        if ($request->method() == 'PATCH') {
            $regrasDinamicas = array();

            foreach ($horario->rules() as $campo => $regra) {
                if (array_key_exists($campo, $request->all())) {
                    $regrasDinamicas[$campo] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $horario->feedback());
        } else {
            $request->validate($horario->rules(), $horario->feedback());
        }

        $horario->update($request->all());
        return response()->json(['status' => 'Horário atualizado com successo!', 'horario' => $horario], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $horario = $this->horarios->find($id);

        if ($horario == null) {
            return response()->json(['status' => 'Horário não marcado!'], 404);
        } else {
            $horario->delete();
            return response()->json(['status' => 'Horário removido com successo!', 'horario' => $horario]);
        }
    }
}
