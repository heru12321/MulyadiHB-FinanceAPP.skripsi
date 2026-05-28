<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTTransaksisTable extends Migration
{
    public function up()
    {
        Schema::create('t_transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('m_users');
            $table->string('kode_inv')->unique();
            $table->boolean('is_lunas')->default(false);
            $table->foreignId('m_pelanggan_id')->constrained('m_pelanggans');
            $table->bigInteger('total_harga')->default(0);
            $table->bigInteger('total_dibayar')->default(0);
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_transaksis');
    }
}
