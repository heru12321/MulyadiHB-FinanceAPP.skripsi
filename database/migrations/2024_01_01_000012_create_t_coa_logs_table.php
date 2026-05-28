<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTCoaLogsTable extends Migration
{
    public function up()
    {
        Schema::create('t_coa_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('m_users');
            $table->date('tanggal');
            $table->foreignId('coa_id')->constrained('m_coas');
            $table->bigInteger('debit')->nullable();
            $table->bigInteger('kredit')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('m_jurnal_id')->constrained('m_jurnal_umums');
            $table->foreignId('t_pembelian_id')->nullable()->constrained('t_pembelians');
            $table->foreignId('t_transaksi_id')->nullable()->constrained('t_transaksis');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_coa_logs');
    }
}
