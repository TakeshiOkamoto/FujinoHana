<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class FujiFile extends Model
{
   protected $guarded = array('id');   
     
   public static function Rules()
   {
      // 親ID/子IDが編集できるもののみ
      return [
          'title'  => 'nullable|max:250',
          'c_memo' => 'nullable|max:64000',
          'p_memo' => 'nullable|max:64000', 
          'status' => 'required|between:0,6',              
          ];
   }
}
