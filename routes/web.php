<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ホーム
Route::get('/', 'HomeController@index')->middleware('login');

// アップロード/ダウンロード
Route::get('download', 'UpDownController@download')->middleware('login');
Route::post('upload', 'UpDownController@upload')->middleware('login');

// ファイル(親 or 子)
Route::get('files', 'FilesController@index')->middleware('login');
Route::get('files/{file}', 'FilesController@show')->middleware('login');
Route::get('files/{file}/edit', 'FilesController@edit')->middleware('login');
Route::put('files/{file}', 'FilesController@update')->middleware('login');
Route::delete('files/{file}', 'FilesController@destroy')->middleware('login');

// ファイル(子) ※子ファイルの管理
Route::get('childfiles', 'ChildFilesController@index')->middleware('admin_login');
Route::get('childfiles/{childfile}', 'ChildFilesController@show')->middleware('admin_login');
Route::get('childfiles/{childfile}/edit', 'ChildFilesController@edit')->middleware('admin_login');
Route::put('childfiles/{childfile}', 'ChildFilesController@update')->middleware('admin_login');
Route::delete('childfiles/{childfile}', 'ChildFilesController@destroy')->middleware('admin_login');

// 基本情報
Route::get('basics', 'BasicsController@index')->middleware('admin_login');
Route::post('basics', 'BasicsController@update')->middleware('admin_login');

// 子ID管理
Route::resource('childs', 'ChildsController')->middleware('admin_login');

// ログイン
Route::get('login', function () {
    return view('login');
});
Route::post('login', 'LoginController@login');
Route::get('logout', 'LoginController@logout')->middleware('login');
