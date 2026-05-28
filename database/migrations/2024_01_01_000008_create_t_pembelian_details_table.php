<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTPembelianDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('t_pembelian_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('t_pembelian_id')->constrained('t_pembelians');
            $table->foreignId('m_stok_id')->constrained('m_stoks');
            $table->bigInteger('harga_beli')->default(0);
            $table->integer('jumlah')->default(0);
            $table->bigInteger('subtotal')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_pembelian_details');
    }
}
