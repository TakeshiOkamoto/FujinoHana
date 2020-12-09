<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// 追加分
use App\FujiFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FilesController extends Controller
{
    public function index(Request $request)
    {   
        // アクセスID
        if (session()->has('parentID')){
            $access_id = session('parentID');  
        }else{
            $access_id = session('childID');  
        }
              
        $items = FujiFile::where('access_id', $access_id);
        $title = Controller::trim($request->title);
           
        if ($title != ""){
            $arr = explode(' ', $title);
            for ($i=0; $i<count($arr); $i++){
                $keyword = str_replace('%', '\%', $arr[$i]);            
                $items = $items->where('title', 'like', "%$keyword%");
            }
        }
        $items = $items->orderBy('created_at','DESC')->paginate(25); 
        
        if (session()->has('parentID')){
            $h1 = trans('validation.attributes.form2');  
        }else{
            $h1 = trans('validation.attributes.form4');  
        }
        
        // HTML準備
        foreach($items as $item){
          // 状態
          $item->status_raw = Controller::getStatusHtml($item->status);
          // 長さ
          $item->filetime = Controller::ConvTimeUnit($item->filetime, !(app()->getLocale() == "ja"));          
        }
        
        return view('files.index',['items' => $items, 'title'=> $title, 'h1' => $h1]);
    }

    public function show($id)
    {
        // アクセスID
        if (session()->has('parentID')){
            $access_id = session('parentID');  
        }else{
            $access_id = session('childID');  
        }
        
        // 基本情報
        $parent_item = Controller::getParentItem();
        if(!isset($parent_item)){
            return redirect(url('/')); 
        }  
                
        $item = FujiFile::where('id', $id)->where('access_id', $access_id)->get();
        if(count($item) === 1){
           $item[0]->status_raw = Controller::getStatusHtml($item[0]->status);
           $item[0]->c_memo     = Controller::html($item[0]->c_memo);
           $item[0]->p_memo     = Controller::html($item[0]->p_memo);           
           $item[0]->filetime   = Controller::ConvTimeUnit($item[0]->filetime, !(app()->getLocale() == "ja")); 
           $item[0]->filesize   = Controller::ConvFileUnit($item[0]->filesize);            
           return view('files.show',['item' => $item[0], 'parent_item' => $parent_item]);
        }else{
           return redirect(url('/'));
        }
    }

    public function edit($id)
    {
        // アクセスID
        if (session()->has('parentID')){
            $access_id = session('parentID');  
        }else{
            $access_id = session('childID');  
        }
        
        $item = FujiFile::where('id', $id)->where('access_id', $access_id)->get();
        if(count($item) === 1){        
           return view('files.edit',['item' => $item[0]]);
        }else{
           return redirect(url('/'));
        }
    }

    public function update(Request $request, $id)
    {      
        // アクセスID
        if (session()->has('parentID')){
            $access_id = session('parentID');  
        }else{
            $access_id = session('childID');  
        }        
 
        $title = Controller::trim($request->title);
        if($title == ""){
            $title = trans('validation.attributes.untitled');
        }
        
        // 子IDは状態のルール除外
        $rules = FujiFile::Rules();
        if (session()->has('childID')){
            $rules['status'] = '';  
        }
        
        // バリデーション
        $param = [
            'title'   => $title,  
            'c_memo'  => Controller::trim($request->c_memo), 
        ]; 
        $request->merge($param);
        $request->validate($rules); 
        
        // 親IDのみ状態を変更可能  
        if (session()->has('parentID')){
            $param['status'] = $request->status;
        }
        
        // 親IDまたは子ID(状態が未設定のみ可)
        $item = FujiFile::where('id', $id)->where('access_id', $access_id)->get(); 
        if ((session()->has('parentID')) || (session()->has('childID') && $item[0]->status == 0)){
            
            // トランザクション      
            DB::transaction(function () use ($param, $access_id, $id) {
            
                FujiFile::where('id', $id)->where('access_id', $access_id)->update($param);   
                         
            });
                    
            // フラッシュ
            session()->flash('flash_flg', 1);
            session()->flash('flash_msg', trans('validation.attributes.msg_update'));
            
            return redirect(url('files'));
        }else{
            abort('403');   
        }
    }

    public function destroy($id)
    {   
        // アクセスID
        if (session()->has('parentID')){
            $access_id = session('parentID');  
        }else{
            $access_id = session('childID');  
        }                         
        $item = FujiFile::where('id', $id)->where('access_id', $access_id)->get(); 
      
        // 親IDまたは子ID(状態が未設定のみ可)
        $filename = $item[0]->filename;
        if ((session()->has('parentID')) || (session()->has('childID') && $item[0]->status == 0)){
          
            // トランザクション
            DB::transaction(function () use ($access_id, $filename,  $id) {
                       
                FujiFile::where('id', $id)->where('access_id', $access_id)->delete();
                $file = base_path() . "/save/" . $filename;
                if (\File::exists($file)) {
                    \File::delete($file);
                }
            });
            
            // フラッシュ
            session()->flash('flash_flg', 0);
            session()->flash('flash_msg', trans('validation.attributes.msg_delete'));
        }else{
            abort('403'); 
        } 
    }
}
