<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMUsersTable extends Migration
{
    public function up()
    {
        Schema::create('m_users', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nama_perusahaan');
            $table->string('password');
            $table->string('no_telp')->nullable();
            $table->string('email')->unique();
            $table->text('alamat')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_users');
    }
}
