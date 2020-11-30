<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// 追加分
use App\FujiParent;
use App\FujiChild;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // 子ID
        $child = true;
        $user = FujiChild::where('login_id', $request->login_id)->get();
        if (count($user) === 0){
            // 親ID
            $user = FujiParent::where('login_id', $request->login_id)->get();
            if (count($user) === 0){
                return view('login', ['login_error' => '1']);
            }
            $child = false;
        }
        
        // 一致
        if (Hash::check($request->password, $user[0]->password)) {
            
            session()->forget('parentID');
            session()->forget('childID');
            
            // 子ID
            if ($child){
              session(['childID' => $user[0]->login_id]); 
            // 親ID  
            }else{
              session(['parentID' => $user[0]->login_id]);            
            }
            session(['name' => $user[0]->name]);      
                         
            // フラッシュ
            session()->flash('flash_flg', 1);
            session()->flash('flash_msg', trans('validation.attributes.msg_login'));
                  
            return redirect(url('/'));
            
        // 不一致    
        }else{
            return view('login', ['login_error' => '1']);
        }
    } 
    
    public function logout(Request $request)
    {
        session()->forget('name');
        session()->forget('parentID');
        session()->forget('childID');
        return redirect(url('login'));
    }  
}
