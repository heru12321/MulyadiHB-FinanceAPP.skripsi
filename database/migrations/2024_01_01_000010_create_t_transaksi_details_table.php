<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTTransaksiDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('t_transaksi_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('t_transaksi_id')->constrained('t_transaksis');
            $table->foreignId('m_stok_id')->constrained('m_stoks');
            $table->bigInteger('harga_satuan')->default(0);
            $table->integer('jumlah')->default(0);
            $table->bigInteger('subtotal')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_transaksi_details');
    }
}
