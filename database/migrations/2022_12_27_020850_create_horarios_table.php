<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->datetime('horario');
            $table->string('servico');
            $table->string('obs')->nullable();
            $table->float('valor');
            $table->string('pagamento');
            $table->timestamps();

            // Relacionamento ID Usuário para FK Usuário
            $table->unsignedBigInteger('usuario')->nullable();
            $table->foreign('usuario')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('horarios');
    }
};
