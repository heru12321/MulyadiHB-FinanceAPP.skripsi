<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMCoasTable extends Migration
{
    public function up()
    {
        Schema::create('m_coas', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();
            $table->string('nama');
            $table->foreignId('kategori_id')->constrained('m_kategori_coas');
            $table->enum('tipe_saldo', ['debit', 'kredit']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_coas');
    }
}
