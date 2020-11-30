<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class FujiChild extends Model
{
   protected $table = 'fuji_childs';   
     
   protected $guarded = array('id');   
     
   public static function Rules()
   {
      return [
          'name'     => 'required|unique:fuji_childs|max:50',
          'login_id' => 'required|unique:fuji_parents|unique:fuji_childs|max:30',
          'password' => 'required|confirmed|min:8|max:50',
          ];
   }
}
