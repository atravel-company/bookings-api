<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->boolean('estado')->default(true);
            $table->unsignedBigInteger('categoria_id')->nullable();
            $table->unsignedBigInteger('destino_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->boolean('alojamento')->default(false);
            $table->boolean('golf')->default(false);
            $table->boolean('transfer')->default(false);
            $table->boolean('car')->default(false);
            $table->boolean('ticket')->default(false);
            $table->string('email')->nullable();
            $table->string('email_type')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // Foreign key constraints (remove if not needed)
            $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('set null');
            $table->foreign('destino_id')->references('id')->on('destinos')->onDelete('set null');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop foreign keys first
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
            $table->dropForeign(['destino_id']);
            $table->dropForeign(['supplier_id']);
        });

        Schema::dropIfExists('produtos');
    }
}
