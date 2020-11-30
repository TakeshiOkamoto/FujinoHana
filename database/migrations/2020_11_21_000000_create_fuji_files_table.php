<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFujiFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 音声ファイル
        Schema::create('fuji_files', function (Blueprint $table) {

            $table->bigIncrements('id');

            // アクセスできるID 
            // ※親の場合は親ID、子の場合は子ID
            $table->string('access_id')->index();   
            // ファイル名
            $table->string('filename')->unique();  
            // タイトル
            $table->string('title')->nullable()->index(); 
            // 一言(子)
            $table->text('c_memo')->nullable();
            // 一言(親)
            $table->text('p_memo')->nullable();
            // 状態
            // ※デフォルト(未選択) = 0
            $table->integer('status');
            // ファイルサイズ
            $table->integer('filesize');
            // 再生時間(秒)
            $table->double('filetime'); 
            
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
        Schema::dropIfExists('fuji_files');
    }
}
