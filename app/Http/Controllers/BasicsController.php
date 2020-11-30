<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// 追加分
use App\FujiParent;
use App\FujiFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BasicsController extends Controller
{   
    public function index(Request $request)
    {   
        // 基本情報
        $item = Controller::getParentItem();
        if(!isset($item)){
            return redirect(url('/')); 
        }  

        // 統計
        $count = DB::select("SELECT count(id) as cnt  FROM fuji_files");  
        $time  = DB::select("SELECT SUM(filetime) as seconds  FROM fuji_files");  
        $size  = DB::select("SELECT SUM(filesize) as size  FROM fuji_files");  
        
        $size = Controller::ConvFileUnit($size[0]->size);  
        
        if (app()->getLocale() == "ja"){
          $time = Controller::ConvTimeUnit($time[0]->seconds, false);   
        }else{
          $time = Controller::ConvTimeUnit($time[0]->seconds, true);
        }
        
        return view('basics.index',['item'  => $item,
                                    'count' => number_format($count[0]->cnt), 
                                    'time'  => $time,
                                    'size'  => $size,
                                   ]);
    }

    public function update(Request $request)
    {      
        // 基本情報
        $item = Controller::getParentItem();
        if(!isset($item)){
            return redirect(url('/')); 
        }  
        $id = $item->id;     
        
        // 自分自身のlogin_idのユニークを確認しない
        $rules = FujiParent::Rules();
        $rules['login_id'] = 'required|max:30|unique:fuji_childs|unique:fuji_parents,login_id,' . $id . ',id';   
        
        // バリデーション
        $param = [];
        if (isset($request->password)){          
          $param = [
            'name'      => Controller::trim($request->name),  
            'g_name'    => Controller::trim($request->g_name),  
            'g_info'    => Controller::trim($request->g_info),  
            'g_memo'    => Controller::trim($request->g_memo), 
            'kbps'      => $request->kbps, 
            'login_id'  => Controller::trim($request->login_id),  
            'password'  => Controller::trim($request->password),  
          ]; 
          $request->merge($param); 
          
          // ハッシュ
          $param['password'] = Hash::make($param['password']); 
        }else{
          $param = [
            'name'      => Controller::trim($request->name),  
            'g_name'    => Controller::trim($request->g_name),  
            'g_info'    => Controller::trim($request->g_info),  
            'g_memo'    => Controller::trim($request->g_memo), 
            'kbps'      => $request->kbps, 
            'login_id'  => Controller::trim($request->login_id),  
          ];
          $request->merge($param);
          
          $rules['password'] = '';
        } 
        $request->validate($rules);  
          
        // トランザクション        
        DB::transaction(function () use ($param, $id) {    
                
            // データ更新
            FujiParent::where('id', $id)->update($param);
            
            // 各ファイルのアクセスIDの更新
            FujiFile::where('access_id' ,[session('parentID')])->update(['access_id' => $param['login_id']]);
            
            // セッションの更新
            session(['name'      => $param['name']]);              
            session(['parentID'  => $param['login_id']]);   
        });
                
        // フラッシュ
        session()->flash('flash_flg', 1);
        session()->flash('flash_msg', trans('validation.attributes.msg_update'));
        
        return redirect(url('basics')); 
    }
}
