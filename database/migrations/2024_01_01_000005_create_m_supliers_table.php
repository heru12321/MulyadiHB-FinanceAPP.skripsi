<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMSupliersTable extends Migration
{
    public function up()
    {
        Schema::create('m_supliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('m_users');
            $table->string('nama');
            $table->string('no_telp')->nullable();
            $table->bigInteger('total_hutang')->default(0);
            $table->bigInteger('hutang_dibayar')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_supliers');
    }
}
