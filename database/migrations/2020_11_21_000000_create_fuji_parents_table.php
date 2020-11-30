<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFujiParentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 親ID(管理者)
        Schema::create('fuji_parents', function (Blueprint $table) {
        
            $table->bigIncrements('id');
            
            // 名前
            $table->string('name');
            // グループ名
            $table->string('g_name')->nullable();
            // グループ情報
            $table->text('g_info')->nullable(); 
            // グループメモ           
            $table->text('g_memo')->nullable();
            // 音質
            // デフォルト(3) = 64kbps(データ小)
            $table->integer('kbps');
            // ログインID
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
        Schema::dropIfExists('fuji_parents');
    }
}
