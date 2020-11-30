<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class FujiParent extends Authenticatable
{
   protected $guarded = array('id');   
   
   public static function Rules()
   {
      return [
          'name'     => 'required|max:50',
          'g_name'   => 'nullable|max:250',
          'g_info'   => 'nullable|max:64000',
          'g_memo'   => 'nullable|max:64000',           
          'kbps'     => 'required|between:1,12',
          'login_id' => 'required|unique:fuji_parents|unique:fuji_childs|max:30',
          'password' => 'required|confirmed|min:8|max:50',
          ];
   }
}
