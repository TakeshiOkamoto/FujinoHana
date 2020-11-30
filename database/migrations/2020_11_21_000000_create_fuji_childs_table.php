<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFujiChildsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 子ID
        Schema::create('fuji_childs', function (Blueprint $table) {

            $table->bigIncrements('id');

            // 名前
            $table->string('name')->unique();
            // ログインID
            // ※プログラム上、親IDと同じIDは登録不可にしている
            $table->string('login_id')->unique(); 
            // ログインパスワード
            $table->string('password');
            
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fuji_childs');
    }
}
