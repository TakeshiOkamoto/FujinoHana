<?php

// 元ファイル
// https://readouble.com/laravel/6.x/ja/validation-php.html
//

return [

    /*
    |--------------------------------------------------------------------------
    | バリデーション言語行
    |--------------------------------------------------------------------------
    |
    | 以下の言語行はバリデタークラスにより使用されるデフォルトのエラー
    | メッセージです。サイズルールのようにいくつかのバリデーションを
    | 持っているものもあります。メッセージはご自由に調整してください。
    |
    */

    'accepted'             => ':attributeを承認してください。',
    'active_url'           => ':attributeが有効なURLではありません。',
    'after'                => ':attributeには、:dateより後の日付を指定してください。',
    'after_or_equal'       => ':attributeには、:date以降の日付を指定してください。',
    'alpha'                => ':attributeはアルファベットのみがご利用できます。',
    'alpha_dash'           => ':attributeはアルファベットとダッシュ(-)及び下線(_)がご利用できます。',
    'alpha_num'            => ':attributeはアルファベット数字がご利用できます。',
    'array'                => ':attributeは配列でなくてはなりません。',
    'before'               => ':attributeには、:dateより前の日付をご利用ください。',
    'before_or_equal'      => ':attributeには、:date以前の日付をご利用ください。',
    'between'              => [
        'numeric' => ':attributeは、:minから:maxの間で指定してください。',
        'file'    => ':attributeは、:min kBから、:max kBの間で指定してください。',
        'string'  => ':attributeは、:min文字から、:max文字の間で指定してください。',
        'array'   => ':attributeは、:min個から:max個の間で指定してください。',
    ],
    'boolean'              => ':attributeは、trueかfalseを指定してください。',
    'confirmed'            => ':attributeと、確認フィールドとが、一致していません。',
    'date'                 => ':attributeには有効な日付を指定してください。',
    'date_equals'          => ':attributeには、:dateと同じ日付けを指定してください。',
    'date_format'          => ':attributeは:format形式で指定してください。',
    'different'            => ':attributeと:otherには、異なった内容を指定してください。',
    'digits'               => ':attributeは:digits桁で指定してください。',
    'digits_between'       => ':attributeは:min桁から:max桁の間で指定してください。',
    'dimensions'           => ':attributeの図形サイズが正しくありません。',
    'distinct'             => ':attributeには異なった値を指定してください。',
    'email'                => ':attributeには、有効なメールアドレスを指定してください。',
    'ends_with'            => ':attributeには、:valuesのどれかで終わる値を指定してください。',
    'exists'               => '選択された:attributeは正しくありません。',
    'file'                 => ':attributeにはファイルを指定してください。',
    'filled'               => ':attributeに値を指定してください。',
    'gt'                   => [
        'numeric' => ':attributeには、:valueより大きな値を指定してください。',
        'file'    => ':attributeには、:value kBより大きなファイルを指定してください。',
        'string'  => ':attributeは、:value文字より長く指定してください。',
        'array'   => ':attributeには、:value個より多くのアイテムを指定してください。',
    ],
    'gte'                  => [
        'numeric' => ':attributeには、:value以上の値を指定してください。',
        'file'    => ':attributeには、:value kB以上のファイルを指定してください。',
        'string'  => ':attributeは、:value文字以上で指定してください。',
        'array'   => ':attributeには、:value個以上のアイテムを指定してください。',
    ],
    'image'                => ':attributeには画像ファイルを指定してください。',
    'in'                   => '選択された:attributeは正しくありません。',
    'in_array'             => ':attributeには:otherの値を指定してください。',
    'integer'              => ':attributeは整数で指定してください。',
    'ip'                   => ':attributeには、有効なIPアドレスを指定してください。',
    'ipv4'                 => ':attributeには、有効なIPv4アドレスを指定してください。',
    'ipv6'                 => ':attributeには、有効なIPv6アドレスを指定してください。',
    'json'                 => ':attributeには、有効なJSON文字列を指定してください。',
    'lt'                   => [
        'numeric' => ':attributeには、:valueより小さな値を指定してください。',
        'file'    => ':attributeには、:value kBより小さなファイルを指定してください。',
        'string'  => ':attributeは、:value文字より短く指定してください。',
        'array'   => ':attributeには、:value個より少ないアイテムを指定してください。',
    ],
    'lte'                  => [
        'numeric' => ':attributeには、:value以下の値を指定してください。',
        'file'    => ':attributeには、:value kB以下のファイルを指定してください。',
        'string'  => ':attributeは、:value文字以下で指定してください。',
        'array'   => ':attributeには、:value個以下のアイテムを指定してください。',
    ],
    'max'                  => [
        'numeric' => ':attributeには、:max以下の数字を指定してください。',
        'file'    => ':attributeには、:max kB以下のファイルを指定してください。',
        'string'  => ':attributeは、:max文字以下で指定してください。',
        'array'   => ':attributeは:max個以下指定してください。',
    ],
    'mimes'                => ':attributeには:valuesタイプのファイルを指定してください。',
    'mimetypes'            => ':attributeには:valuesタイプのファイルを指定してください。',
    'min'                  => [
        'numeric' => ':attributeには、:min以上の数字を指定してください。',
        'file'    => ':attributeには、:min kB以上のファイルを指定してください。',
        'string'  => ':attributeは、:min文字以上で指定してください。',
        'array'   => ':attributeは:min個以上指定してください。',
    ],
    'not_in'               => '選択された:attributeは正しくありません。',
    'not_regex'            => ':attributeの形式が正しくありません。',
    'numeric'              => ':attributeには、数字を指定してください。',
    'present'              => ':attributeが存在していません。',
    'regex'                => ':attributeに正しい形式を指定してください。',
    'required'             => ':attributeは必ず指定してください。',
    'required_if'          => ':otherが:valueの場合、:attributeも指定してください。',
    'required_unless'      => ':otherが:valuesでない場合、:attributeを指定してください。',
    'required_with'        => ':valuesを指定する場合は、:attributeも指定してください。',
    'required_with_all'    => ':valuesを指定する場合は、:attributeも指定してください。',
    'required_without'     => ':valuesを指定しない場合は、:attributeを指定してください。',
    'required_without_all' => ':valuesのどれも指定しない場合は、:attributeを指定してください。',
    'same'                 => ':attributeと:otherには同じ値を指定してください。',
    'size'                 => [
        'numeric' => ':attributeは:sizeを指定してください。',
        'file'    => ':attributeのファイルは、:sizeキロバイトでなくてはなりません。',
        'string'  => ':attributeは:size文字で指定してください。',
        'array'   => ':attributeは:size個指定してください。',
    ],
    'starts_with'          => ':attributeには、:valuesのどれかで始まる値を指定してください。',
    'string'               => ':attributeは文字列を指定してください。',
    'timezone'             => ':attributeには、有効なゾーンを指定してください。',
    'unique'               => ':attributeの値は既に存在しています。',
    'uploaded'             => ':attributeのアップロードに失敗しました。',
    'url'                  => ':attributeに正しい形式を指定してください。',
    'uuid'                 => ':attributeに有効なUUIDを指定してください。',

    /*
    |--------------------------------------------------------------------------
    | Custom バリデーション言語行
    |--------------------------------------------------------------------------
    |
    | "属性.ルール"の規約でキーを指定することでカスタムバリデーション
    | メッセージを定義できます。指定した属性ルールに対する特定の
    | カスタム言語行を手早く指定できます。
    |
    */

    'custom' => [
        '属性名' => [
            'ルール名' => 'カスタムメッセージ',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | カスタムバリデーション属性名
    |--------------------------------------------------------------------------
    |
    | 以下の言語行は、例えば"email"の代わりに「メールアドレス」のように、
    | 読み手にフレンドリーな表現でプレースホルダーを置き換えるために指定する
    | 言語行です。これはメッセージをよりきれいに表示するために役に立ちます。
    |
    */
    
    // 追加分
    'attributes' => [
    
        // Database        
        "created_at" => "作成日時",
        "updated_at" => "更新日時",
        
        "name" => "名前",
        "g_name" => "グループ名",
        "g_info" => "グループ情報",
        "g_memo" => "グループメモ",
        "kbps" => "音質",
        "login_id" => "ログインID",
        "password" => "パスワード",
        "password_confirmation" => "パスワードの確認",
        
        "title" => "タイトル",
        "c_memo" => "メッセージ", 
        "p_memo" => "メッセージ",
        "status" => "状態",      
        
        // Form
        "app_name" => "FujinoHana - 音声録音管理システム",  
                
        "form1" => "ホーム",  
        "form2" => "ファイル(親)",  
        "form3" => "ファイル(子)",  
        "form4" => "ファイル",          
        "form5" => "基本情報",  
        "form6" => "子ID管理",  
        "form7" => "ログアウト",  
        "form8" => "ログイン",         
                
        // System       
        "msg_create" => "登録しました。",
        "msg_update" => "更新しました。", 
        "msg_delete" => "削除しました。", 
        "msg_login" => "ログインしました。",         
          
        "msg_name_search" => "検索したい名前を入力",  
        "msg_title_search" => "検索したいタイトルを入力",          
        "msg_none" => "データがありません。",  
        "msg_password" => "※パスワードを変更しない場合は空のままにして下さい。",  
        "msg_login_error" => "ログインIDまたはパスワードが一致しません。",  
        "msg_mp3_info" => "※数値を大きくすると音質が向上してファイルサイズが増加します。(初期設定:64kbps)",  
        "msg_mp3_progress" => "MP3に変換中 ...",    
        "msg_mp3_recording" => "MP3で録音中 ...",              
        "msg_mp3_upload" => "アップロード中 ...",                 
        "msg_alert1" => "お使いのInternet Explorerでは録音できません。",     
        "msg_alert2" => "※Chrome/Firefox/Edgeなどをご利用ください。",     
        "msg_null" => "※空でも可",     
        "msg_null_plus" => "※空でも可、改行、URLもOK",     
        "msg_browser_pc" => "※30分以上録音する場合、ブラウザはChromeを使用して下さい。",   
        "msg_browser_sp" => "スマートフォンでの録音は30分以内にして下さい。Chrome推奨。",           
        "msg_microphone" => "マイクに接続できませんでした。", 
            
        "msg_error_413" => "ERROR(413) : サーバー側の設定が不十分です。\n※管理者の方は公式(GitHub)の「サーバー設定」を参照", 
        "msg_error_419" => "ERROR(419) : ログインしてください。", 
        "msg_error_500" => "ERROR(500) : ファイルのアップロードに失敗しました 。", 
                                 
        "basics1"   => "ファイル数", 
        "basics2"   => "音声の長さ (合計)", 
        "basics3"   => "ディスクの使用量 (合計)", 
                          
        "mp3"   => "MP3設定", 
        "mp3_1" => "32kbps(データ最小)", 
        "mp3_2" => "48kbps(データ小)",
        "mp3_3" => "64kbps(データ小)", 
        "mp3_4" => "80kbps(データ小)",  
        "mp3_5" => "96bps(データ小)", 
        "mp3_6" => "112kbps(音質普通)",
        "mp3_7" => "128kbps(音質普通)", 
        "mp3_8" => "160kbps(音質普通)", 
        "mp3_9" => "192kbps(音質良い・高速)", 
        "mp3_10" => "224kbps(音質良い・高速)", 
        "mp3_11" => "256kbps(音質良い・高速)", 
        "mp3_12" => "320kbps(音質最良・最速)",                                                                                                       
        
        "status_0" => "未設定",                  
        "status_1" => "確認",                                                                                                       
        "status_2" => "合格",                                                                                                       
        "status_3" => "不合格",                                                                                                       
        "status_4" => "承認",                                                                                                       
        "status_5" => "否認",                                                                                                       
        "status_6" => "保留",                                                                                                       
               
        "new" => "新規登録",  
        "create" => "登録する",
        "update" => "更新する", 
        "edit" => "編集",
        "delete" => "削除", 
        "search" => "検索", 
        "back" => "戻る", 
        "login" => "ログインする",  
        "group" => "グループ",  
        "account" => "アカウント",
        "user" => "ユーザー名：",        
        "record" => "録 音",
        "upload" => "アップロード", 
        "cancel" => "キャンセル", 
        "info" => "インフォメーション", 
        "untitled" => "無題",    
        "statistics" => "統計",   
        "length" => "長さ", 
        "filesize" => "ファイルサイズ",         
        "download" => "ダウンロード",                                     
    ],

];