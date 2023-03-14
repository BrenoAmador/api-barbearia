<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horarios extends Model
{
    use HasFactory;

    protected $fillable = [
        'horario',
        'servico',
        'valor',
        'pagamento',
        'obs',
        'usuario'
    ];

    public function rules(){
        return [
            'horario' => 'required',
            'servico' => 'required',
            'valor' => 'required',
            'pagamento' => 'required'
        ];
    }

    public function feedback(){
        return [
            'required' => 'Campo obrigatório.'
        ];
    }

    public function usuario()
    {
        // Um Horário pertence a UM Usuario
        return $this->belongsTo(User::class, 'usuario');
    }
}
