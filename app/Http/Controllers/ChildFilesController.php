<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// 追加分
use App\FujiFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ChildFilesController extends Controller
{
    public function index(Request $request)
    {   
        $items = FujiFile::leftJoin('fuji_childs', 'fuji_files.access_id', '=', 'fuji_childs.login_id')
                   ->where('access_id', '!=', session('parentID'))
                   ->select('fuji_files.*', 'fuji_childs.name');
        $name = Controller::trim($request->name);
           
        if ($name != ""){
            $arr = explode(' ', $name);
            for ($i=0; $i<count($arr); $i++){
                $keyword = str_replace('%', '\%', $arr[$i]);            
                $items = $items->where('name', 'like', "%$keyword%");
            }
        }
        $items = $items->orderBy('created_at','DESC')->paginate(25); 
        
        // HTML準備
        foreach($items as $item){
          // 状態
          $item->status_raw = Controller::getStatusHtml($item->status);
          // 長さ
          $item->filetime = Controller::ConvTimeUnit($item->filetime, !(app()->getLocale() == "ja"));          
        }
        
        return view('childfiles.index',['items' => $items, 'name'=> $name]);
    }

    public function show($id)
    {
        $item = FujiFile::leftJoin('fuji_childs', 'fuji_files.access_id', '=', 'fuji_childs.login_id')
                  ->where('fuji_files.id', $id)
                  ->select('fuji_files.*', 'fuji_childs.name')
                  ->get();                  
                  
        if(count($item) === 1){
           $item[0]->status_raw = Controller::getStatusHtml($item[0]->status);
           $item[0]->c_memo     = Controller::html($item[0]->c_memo);
           $item[0]->p_memo     = Controller::html($item[0]->p_memo);           
           $item[0]->filetime   = Controller::ConvTimeUnit($item[0]->filetime, !(app()->getLocale() == "ja")); 
           $item[0]->filesize   = Controller::ConvFileUnit($item[0]->filesize);            
           return view('childfiles.show',['item' => $item[0]]);
        }else{
           return redirect(url('/'));
        }
    }

    public function edit($id)
    {        
        $item = FujiFile::leftJoin('fuji_childs', 'fuji_files.access_id', '=', 'fuji_childs.login_id')
                  ->where('fuji_files.id', $id)
                  ->select('fuji_files.*', 'fuji_childs.name')
                  ->get();                          
        if(count($item) === 1){        
           return view('childfiles.edit',['item' => $item[0]]);
        }else{
           return redirect(url('/'));
        }
    }

    public function update(Request $request, $id)
    {       
        $title = Controller::trim($request->title);
        if($title == ""){
            $title = trans('validation.attributes.untitled');
        }        
        
        // バリデーション
        $param = [
            'title'   => $title,  
            'c_memo'  => Controller::trim($request->c_memo), 
            'p_memo'  => Controller::trim($request->p_memo),
            'status'  => $request->status,
        ]; 
        $request->merge($param);
        $request->validate(FujiFile::Rules());

        // トランザクション      
        DB::transaction(function () use ($param, $id) {
        
            FujiFile::where('id', $id)->update($param);   
                     
        });
                
        // フラッシュ
        session()->flash('flash_flg', 1);
        session()->flash('flash_msg', trans('validation.attributes.msg_update'));
        
        return redirect(url('childfiles'));
    }

    public function destroy($id)
    {   
      // トランザクション
      DB::transaction(function () use ($id) {
                
          $item = FujiFile::where('id', $id)->get(); 
          FujiFile::where('id', $id)->delete();
          $file = base_path() . "/save/" . $item[0]->filename;
          if (\File::exists($file)) {
              \File::delete($file);
          }
      });
      
      // フラッシュ
      session()->flash('flash_flg', 0);
      session()->flash('flash_msg', trans('validation.attributes.msg_delete'));
    }
}
