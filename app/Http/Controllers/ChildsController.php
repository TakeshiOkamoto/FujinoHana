<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// 追加分
use App\FujiChild;
use App\FujiFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ChildsController extends Controller
{
    public function index(Request $request)
    {   
        $items = FujiChild::whereRaw('1=1');
        $name = Controller::trim($request->name);
           
        if ($name != ""){
            $arr = explode(' ', $name);
            for ($i=0; $i<count($arr); $i++){
                $keyword = str_replace('%', '\%', $arr[$i]);            
                $items = $items->where('name', 'like', "%$keyword%");
            }
        }
        $items = $items->orderby('name','ASC')->paginate(25); 
        
        return view('childs.index',['items' => $items, 'name'=> $name]);
    }

    public function create()
    {
        return view('childs.create');
    }

    public function store(Request $request)
    {      
        // パラメータ
        $param = [
            'name'      => Controller::trim($request->name),  
            'login_id'  => Controller::trim($request->login_id),  
            'password'  => Controller::trim($request->password), 
        ];
        $request->merge($param); 
        
        // バリデーション               
        $request->validate(FujiChild::Rules());   
        
        // ハッシュ
        $param['password'] = Hash::make($param['password']);
                
        // トランザクション
        DB::transaction(function () use ($param) {
            $fujichild = new FujiChild;
            $fujichild->fill($param)->save();
        });

        // フラッシュ
        session()->flash('flash_flg', 1);
        session()->flash('flash_msg', trans('validation.attributes.msg_create')); 
        
        return redirect(url('childs'));
    }

    public function show($id)
    {
        $item = FujiChild::where('id', $id)->get();
        if(count($item) === 1){
           return view('childs.show',['item' => $item[0]]);
        }else{
           return redirect(url('/'));
        }
    }

    public function edit($id)
    {
        $item = FujiChild::where('id', $id)->get();
        if(count($item) === 1){
            return view('childs.edit',['item' => $item[0]]);
        }else{
            return redirect(url('/'));
        }
    }

    public function update(Request $request, $id)
    {
        // 自分自身のnameのユニークを確認しない
        $rules = FujiChild::Rules();
        $rules['name']     = 'required|max:50|unique:fuji_childs,name,' . $id . ',id';   
        $rules['login_id'] = '';   
        
        // バリデーション
        $param = [];
        if (isset($request->password)){          
          $param = [
              'name'      => Controller::trim($request->name),  
              'password'  => Controller::trim($request->password),  
          ]; 
          $request->merge($param); 
          
          // ハッシュ
          $param['password'] = Hash::make($param['password']); 
        }else{
          $param = [
              'name'      => Controller::trim($request->name),  
          ];
          $request->merge($param);
          
          $rules['password'] = '';
        } 
        $request->validate($rules);  
          
        // トランザクション      
        DB::transaction(function () use ($param, $id) {
        
            FujiChild::where('id', $id)->update($param);   
                     
        });
                
        // フラッシュ
        session()->flash('flash_flg', 1);
        session()->flash('flash_msg', trans('validation.attributes.msg_update'));
        
        return redirect(url('childs'));
    }

    public function destroy($id)
    {
        // トランザクション
        DB::transaction(function () use ($id) {
        
            // 元データ
            $item = FujiChild::where('id', $id)->get();
            
            // 子IDの削除        
            FujiChild::where('id', $id)->delete();
            
            // (物理)MP3ファイルの削除
            $fujifiles = FujiFile::where('access_id' ,[$item[0]->login_id])->get();
            foreach($fujifiles as $fujifile){
                $file = base_path() . "/save/" . $fujifile->filename;
                if (\File::exists($file)) {
                    \File::delete($file);
                }
            }
            
            // (DB)MP3ファイルの削除
            FujiFile::where('access_id' ,[$item[0]->login_id])->delete();
        });
        
        // フラッシュ
        session()->flash('flash_flg', 0);
        session()->flash('flash_msg', trans('validation.attributes.msg_delete'));
    }
}
