# FujinoHana ( 藤の花 )
  
A system that recording and manages voice that is flowing in microphone or PC with a browser.    
( ブラウザでマイクやパソコン内で流れている音声を録音して管理するシステム )    
  
DEMO ( Japanese )   
[https://www.petitmonte.com/dev/FujinoHana/ja/](https://www.petitmonte.com/dev/FujinoHana/ja/)  
 
DEMO ( English )   
[https://www.petitmonte.com/dev/FujinoHana/en/](https://www.petitmonte.com/dev/FujinoHana/en/)  


## 1. Environment ( 環境 )
・Laravel 6.x or higher  
・MariaDB 10.2.2 or higher (MySQL 5.5 or higher)  
 
## 2. Installation method ( インストール方法 )
  
### Project generation ( プロジェクトの生成 )  
```rb
composer create-project --prefer-dist laravel/laravel FujinoHana  "6.*"
```
Then download the file here and overwrite the project.    
( 次にココにあるファイルをダウンロードして、プロジェクトに上書きします。)

### .env
.env 
```rb
APP_NAME="FujinoHana"

DB_CONNECTION=mysql
DB_DATABASE=xxx
DB_USERNAME=xxx
DB_PASSWORD=xxx 
  
SESSION_LIFETIME=525600 
```
xxx is your database setting.  
( xxxは各自のデータベース設定です。 )  
  
SESSION_LIFETIME = 525600 minutes = 1 year. Please set according to your environment.  
( SESSION_LIFETIME = 525600分 = 1年間です。各自の環境に合わせた設定をして下さい。  )  
### config\app.php  
Japanese setting. ( 日本の設定 )  
```rb
'timezone' => 'Asia/Tokyo',
'locale' => 'ja',
```

### config/session.php
Automatically log out when the browser is closed.  
( ブラウザを閉じたときに自動的にログアウトする。 )  
```rb
'expire_on_close' => true,
```
This setting is any.  
( この設定は任意です。 )
### app\Http\Kernel.php  
Add the following two lines to the end of $routeMiddleware.  
( 次の2行を$routeMiddlewareの最後に追加する。 )  

```rb
protected $routeMiddleware = [
   ...
   
  'login' =>\App\Http\Middleware\LoginMiddleware::class,        
  'admin_login' =>\App\Http\Middleware\AdminLoginMiddleware::class,    
]
```
### routes\api.php

Comment out the following. 
( 以下をコメントにする。 )
```rb
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
```

### database\migrations
Manually delete unnecessary migration files.  
( 不要なマイグレーションファイルを手動で削除する。 )
```rb
2014_10_12_000000_create_users_table.php
2014_10_12_100000_create_password_resets_table.php
2019_08_19_000000_create_failed_jobs_table.php
```
### migrate (マイグレーション)
```rb
php artisan migrate
```
### Creating an administrator account. ( 管理者アカウントの作成 )

```rb
php artisan tinker
```
```rb
$param = [
          'id' => 1,
          'name' => 'your name',
          'kbps' => 3,   // 64kbps
          'login_id' => 'admin',
          'password' => Hash::make('12345678')
         ];
   
DB::table('fuji_parents')->insert($param);

exit;
```
Please set freely except id and kbps.  
( id、kbps以外は自由に設定して下さい。 )  

### Server Settings ( サーバー設定 )
Recording for 60 minutes with MP3 settings (default: 64kbps) will create a file of about 28 MB.  
( MP3設定(デフォルト：64kbps)で60分の録音を行うと約28MBのファイルが作成されます。 )  
  
It is good to set post_max_size / upload_max_filesize in php.ini to about 128MB-256MB on the server side.  
( サーバー側でphp.iniのpost_max_size/upload_max_filesizeの設定を128MB～256MBぐらいにしておくと良いです。 )  

```rb
[php.ini]
post_max_size = 128M
upload_max_filesize = 128M
```
By the way, if the MP3 setting is 128kbps, it will be about 50MB in 60 minutes.  
( ちなみに、MP3設定が128kbpsだと60分で約50MBぐらいになります。 )  
  
In addition, if the web server is Nginx, you also need to set the * .conf file.  
(その他にWebサーバーがNginxの場合は*.confファイルの設定も必要です。 )  

Set client_max_body_size to the same value as php.ini.  
( client_max_body_sizeの設定をphp.iniと同じ値にして下さい。 )  
  
```rb
[*.conf]
client_max_body_size 128m;
```
* Please set as appropriate at your own discretion. 
* The location of php.ini can be confirmed by writing {{ phpinfo () }} on the view side.
* Please note that if these values are insufficient, "ERROR (413)" will occur when uploading.
  
※各自の判断で適宜、設定して下さい。  
※php.iniの場所はビュー側で {{ phpinfo() }} を記述すると確認可能です。   
※これらの値が不足するとアップロード時に「ERROR(413)」が発生しますのでご注意ください。   

### Laravel 8 settings
For Laravel 8, add the following line.  
( Laravel8の場合は以下の1行を追加します。)  
  
app\Providers\RouteServiceProvider.php 
```rb
class RouteServiceProvider extends ServiceProvider
{
    
    protected $namespace = 'App\Http\Controllers';
    
}
```
If you get the following error. ( 次のエラーが発生した場合 )
```rb
SQLSTATE[HY000] [2002] php_network_getaddresses: getaddrinfo failed: Name or service not known
```
.env  
Please change as follows.  ( 以下のように変更して下さい。 )
```rb
DB_HOST=127.0.0.1
#DB_HOST=mysql
```

### RUN ( 実行する )
```rb
php artisan serve
```
[http://localhost:8000/](http://localhost:8000/)   
 
## License
  
MIT license  
  
## Using Library

Recorderjs (https://github.com/mattdiamond/Recorderjs)
```rb
Copyright © 2013 Matt Diamond
```

lamejs (https://github.com/zhuker/lamejs)
```rb
Copyright © zhuker
```

LAME (https://lame.sourceforge.io/)
```rb
Copyright © The LAME development team
```

Bootstrap v4.3.1 (https://getbootstrap.com/)  
```rb
Copyright 2011-2019 The Bootstrap Authors  
Copyright 2011-2019 Twitter, Inc.
```

## 3. Laravelプロジェクトの各種初期設定 ( Japanese )
その他は次の記事を参照してください。  
  
[Laravelプロジェクトの各種初期設定](https://www.petitmonte.com/php/laravel_project.html)  
