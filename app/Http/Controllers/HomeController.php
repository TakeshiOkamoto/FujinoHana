<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// 追加分
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function index(Request $request)
    {   
        // 基本情報
        $item = Controller::getParentItem();
        if(!isset($item)){
            abort('500'); 
        }  
        
        return view('index',['item'   => $item,
                             'g_memo' => Controller::html($item->g_memo),
                             'g_info' => Controller::html($item->g_info),
                             'home'   => true]);
    }

}
