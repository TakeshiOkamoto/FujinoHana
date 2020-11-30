<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// 追加分
use App\FujiFile;
use Illuminate\Support\Facades\DB;

class UpDownController extends Controller
{
    // ダウンロード
    public function download(Request $request)
    {   
        // 管理者は全てのファイルをダウンロード可能
        $obj = FujiFile::where('filename', $request->f);
        if (session()->has('parentID')){
            $item = $obj->get();
        }else{
            // 子IDは自分のファイルのみ
            $item = $obj->where('access_id', session('childID'))->get();
        }

        if (count($item) === 0){
            abort('403');    
        }else{  
            $file = base_path() . "/save/" . $request->f;             

            if(!file_exists($file)){
                abort('404'); 
            }                  
            
            header('Content-Type: audio/mpeg');
            header('Content-Length: ' . filesize($file));
            header('Content-Disposition: attachment; filename="' . $request->f . '"');
            
            // これがないとChrome/Egdeではaudioタグでシークできない
            header('Accept-Ranges: bytes'); 
            
            readfile($file);
        }
    }
    
    // アップロード
    public function upload(Request $request)
    {   
        // AjaxからのMP3の受け取り
        $bindata = $request->getContent();
        $len = strlen($bindata);
        if($len === 0){ 
            abort('500');  
        }        
        
        // アクセスID
        if (session()->has('parentID')){
            $access_id = session('parentID');  
        }else{
            $access_id = session('childID');  
        }
        
        // ファイル名(yyyymmddhhiissvvv)
        $filename = \Carbon\Carbon::now()->format('YmdHisv') . '.mp3';        
        
        $param =[
            'access_id' => $access_id,
            'filename'  => $filename,
            'title'     => $request->title,
            'status'    => 0, // 未選択
            'filesize'  => $len,
            'filetime'  => $request->time,             
        ];

        // ファイルの作成
        $file = base_path() . "/save/" . $filename;
        file_put_contents($file, $bindata);        
        if(!file_exists($file)){
            abort('500'); 
        }      
        
        // トランザクション
        DB::transaction(function () use ($param) {
            $fujifile = new FujiFile;
            $fujifile->fill($param)->save();
        });
    }    
}
