<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMStoksTable extends Migration
{
    public function up()
    {
        Schema::create('m_stoks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('m_users');
            $table->string('nama');
            $table->string('sku')->unique();
            $table->text('deskripsi')->nullable();
            $table->bigInteger('harga')->default(0);
            $table->integer('jumlah_stok')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_stoks');
    }
}
