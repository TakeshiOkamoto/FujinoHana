<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

// 追加分
use App\FujiParent;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    // 全角 => 半角変換 + trim
    public static function trim($str){
        if (isset($str)){
            // a 全角英数字を半角へ
            // s 全角スペースを半角へ
            return trim(mb_convert_kana($str, 'as'));
        }else{
            return "";
        }
    }    
    
    // URLをリンクに変換する(簡易的)
    public static function auto_link($text){      
        $result = $text;
        
        if(isset($result)){
                          
            // URLパターン(予約文字 + 非予約文字 + %)
            //
            // <参考>
            // https://www.asahi-net.or.jp/~ax2s-kmtn/ref/uric.html
            // https://www.petitmonte.com/php/regular_expression_matome.html
            // 
            // 次のvalidateUrl()も参考になる ※先頭の^と末尾の$を削除して使用する
            // laravel\framework\src\Illuminate\Validation\Concerns/ValidatesAttributes.php 
            $pattern ='/(http|https):\/\/[!#$%&\'()*+,\/:;=?@\[\]0-9A-Za-z-._~]+/';
            
            // URLをaタグに変換する
            $result = preg_replace_callback($pattern, function ($matches) {   
                        return '<a href="' . $matches[0] . '">'. $matches[0] . '</a>';
                      }, $result);
        }
        return $result;            
    }
      
    // ファイルサイズからファイル単位に変換する
    public static function ConvFileUnit($filesize){
      
        $kb = 1024;        // KB
        $mb = pow($kb, 2); // MB
        $gb = pow($kb, 3); // GB
        $tb = pow($kb, 4); // TB
        
        // TB
        if($filesize >= $tb){
            return number_format(round($filesize / $tb , 2), 2, '.', ',') . 'TB'; 
        // GB           
        }else if ($filesize >= $gb){
            return number_format(round($filesize / $gb , 2), 2, '.', ',') . 'GB';
        // MB
        }else if($filesize >= $mb){
            return number_format(round($filesize / $mb , 2), 2, '.', ',') . 'MB';
        // KB    
        }else{
            return number_format(round($filesize / $kb , 2), 2, '.', ',') . 'KB';
        }
    }
    
    // 秒から時間単位(?日?時間?分?秒)に変換する    
    public static function ConvTimeUnit($seconds, $en = false){
      
        $m = 60;              // 分
        $h = pow($m, 2);      // 時
        $d = pow($m, 2) * 24; // 日
        
        $time = floor($seconds);
        
        if($en){
            $sday = ' days ';
            $shour = ' hours ';
            $sminute = ' minutes ';
            $ssecond = ' seconds';
        }else{
            $sday = '日';
            $shour = '時間';
            $sminute = '分';
            $ssecond = '秒'; 
        }
        
        if($time >= $d){
          
            // 日
            $day = floor($time / $d);
            $result = $time % $d;
          
            // 時
            $hour = floor($result / $h);
            $result = $result % $h;
          
            // 分/秒
            $minute = floor($result / $m);
            $result = $result % $m;

            return $day . $sday . $hour . $shour . $minute . $sminute . $result .$ssecond;
          
        }else if($time >= $h){
          
            // 時
            $hour = floor($time / $h);
            $result = $time % $h;
          
            // 分/秒
            $minute = floor($result / $m);
            $result = $result % $m;

            return  $hour . $shour . $minute . $sminute . $result .$ssecond;
          
        }else if($time >= $m){          
          
            // 分/秒
            $minute = floor($time / $m);
            $result = $time % $m;

            return  $minute . $sminute . $result .$ssecond;

        }else{
            return   $time . $ssecond;
        }           
    }   
              
    // 文字列をHTML(RAW)に変換する
    public static function html($text){       
        $result = "";
        
        if(isset($text)){
            // エスケープ
            $result = htmlspecialchars($text);            
            // 半角スペース 
            $result = str_replace(" ", '&nbsp;', $result);  
            // タブ 
            $result = str_replace("	", '&nbsp;&nbsp;', $result);  
            // 改行 
            $result = str_replace("\r\n", '<br>', $result);  
            $result = str_replace("\r",   '<br>', $result);  
            $result = str_replace("\n",   '<br>', $result);  
            // URLをaタグに変換する
            // ※既知の問題点 ---> 最初から<a href=""></a>のタグがある場合はその自動リンクが不自然となる             
            $result = Controller::auto_link($result);
        }
        return $result;
    }    
    
    // 状態のHTML(Raw)を取得する
    public static function getStatusHtml($status){  
        switch($status){
            case 1 : return '<span class="badge badge-success">'.  trans('validation.attributes.status_1') . '</span>'; // 確認
            case 2 : return '<span class="badge badge-success">'.  trans('validation.attributes.status_2') . '</span>'; // 合格
            case 3 : return '<span class="badge badge-danger">' .  trans('validation.attributes.status_3') . '</span>'; // 不合格
            case 4 : return '<span class="badge badge-success">'.  trans('validation.attributes.status_4') . '</span>'; // 承認
            case 5 : return '<span class="badge badge-danger">' .  trans('validation.attributes.status_5') . '</span>'; // 否認
            case 6 : return '<span class="badge badge-info">'   .  trans('validation.attributes.status_6') . '</span>'; // 保留
        }   
        return '';
    }    
    
    // 親IDの基本情報を取得する
    public static function getParentItem(){
      
        $item = FujiParent::where('id', '1')->get();
        if(count($item) === 0){
            $item =  FujiParent::whereRaw('1=1')->get();
            if(count($item) === 0){
                return NULL; 
            }  
        } 
        return $item[0];     
    }    
}
