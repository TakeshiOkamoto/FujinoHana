<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'The :attribute must be accepted.',
    'active_url' => 'The :attribute is not a valid URL.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => 'The :attribute may only contain letters.',
    'alpha_dash' => 'The :attribute may only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'The :attribute may only contain letters and numbers.',
    'array' => 'The :attribute must be an array.',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'date' => 'The :attribute is not a valid date.',
    'date_equals' => 'The :attribute must be a date equal to :date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => 'The :attribute must be a valid email address.',
    'ends_with' => 'The :attribute must end with one of the following: :values.',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field must have a value.',
    'gt' => [
        'numeric' => 'The :attribute must be greater than :value.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'string' => 'The :attribute must be greater than :value characters.',
        'array' => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'The :attribute must be greater than or equal :value.',
        'file' => 'The :attribute must be greater than or equal :value kilobytes.',
        'string' => 'The :attribute must be greater than or equal :value characters.',
        'array' => 'The :attribute must have :value items or more.',
    ],
    'image' => 'The :attribute must be an image.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => 'The :attribute must be an integer.',
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'numeric' => 'The :attribute must be less than :value.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'string' => 'The :attribute must be less than :value characters.',
        'array' => 'The :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal :value.',
        'file' => 'The :attribute must be less than or equal :value kilobytes.',
        'string' => 'The :attribute must be less than or equal :value characters.',
        'array' => 'The :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file' => 'The :attribute may not be greater than :max kilobytes.',
        'string' => 'The :attribute may not be greater than :max characters.',
        'array' => 'The :attribute may not have more than :max items.',
    ],
    'mimes' => 'The :attribute must be a file of type: :values.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => 'The :attribute must be at least :min.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'string' => 'The :attribute must be at least :min characters.',
        'array' => 'The :attribute must have at least :min items.',
    ],
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => 'The :attribute must be a number.',
    'password' => 'The password is incorrect.',
    'present' => 'The :attribute field must be present.',
    'regex' => 'The :attribute format is invalid.',
    'required' => 'The :attribute field is required.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'numeric' => 'The :attribute must be :size.',
        'file' => 'The :attribute must be :size kilobytes.',
        'string' => 'The :attribute must be :size characters.',
        'array' => 'The :attribute must contain :size items.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values.',
    'string' => 'The :attribute must be a string.',
    'timezone' => 'The :attribute must be a valid zone.',
    'unique' => 'The :attribute has already been taken.',
    'uploaded' => 'The :attribute failed to upload.',
    'url' => 'The :attribute format is invalid.',
    'uuid' => 'The :attribute must be a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    // Additions
    'attributes' => [
    
        // Database        
        "created_at" => "Create",
        "updated_at" => "Update",
        
        "name" => "Name",
        "g_name" => "Group name",
        "g_info" => "Group information",
        "g_memo" => "Group memo",
        "kbps" => "Sound quality",
        "login_id" => "Login ID",
        "password" => "Password",
        "password_confirmation" => "Confirm password",
        
        "title" => "Title",
        "c_memo" => "Message", 
        "p_memo" => "Message",
        "status" => "Status",      
        
        // Form
        "app_name" => "FujinoHana - voice recording mgt",  
                
        "form1" => "Home",  
        "form2" => "File (parent)",  
        "form3" => "File (child)",  
        "form4" => "File",          
        "form5" => "Basic info",  
        "form6" => "Child ID mgt",  
        "form7" => "Logout",  
        "form8" => "Login",         
                
        // System       
        "msg_create" => "Has registered.",
        "msg_update" => "Has been updated.", 
        "msg_delete" => "Has been deleted.", 
        "msg_login" => "Logged in.",         
          
        "msg_name_search" => "Enter the name you want to search",  
        "msg_title_search" => "Enter the title you want to search",          
        "msg_none" => "There is no data.",  
        "msg_password" => "* If you don't want to change the password, leave it empty.",  
        "msg_login_error" => "The login ID or password does not match.",  
        "msg_mp3_info" => "* Increasing the value will improve the sound quality and increase the file size. (Initial setting: 64kbps)",  
        "msg_mp3_progress" => "Converting to MP3 ...",    
        "msg_mp3_recording" => "Recording with MP3 ...",              
        "msg_mp3_upload" => "Uploading ...",                 
        "msg_alert1" => "You cannot recording with your Internet Explorer.",     
        "msg_alert2" => "* Please use Chrome / Firefox / Edge.",     
        "msg_null" => "* Can be empty",     
        "msg_null_plus" => "* Empty, line breaks, URLs are OK",     
        "msg_browser_pc" => "When recording for 30 minutes or more, please use Chrome.",     
        "msg_browser_sp" => "Recording on smartphone should be 30 minutes or less. Chrome recommended.",       
        "msg_microphone" => "Could not connect to the microphone.", 
            
        "msg_error_413" => "ERROR(413) : Insufficient settings on the server side.\n* Administrators refer to the official (GitHub) 'Server Settings'", 
        "msg_error_419" => "ERROR(419) : Please login.", 
        "msg_error_500" => "ERROR(500) : File upload failed.", 
                                 
        "basics1"   => "Number of files", 
        "basics2"   => "Audio length (total)", 
        "basics3"   => "Disk usage (total)", 
                          
        "mp3"   => "MP3 settings", 
        "mp3_1" => "32kbps", 
        "mp3_2" => "48kbps",
        "mp3_3" => "64kbps", 
        "mp3_4" => "80kbps",  
        "mp3_5" => "96bps", 
        "mp3_6" => "112kbps",
        "mp3_7" => "128kbps", 
        "mp3_8" => "160kbps", 
        "mp3_9" => "192kbps", 
        "mp3_10" => "224kbps", 
        "mp3_11" => "256kbps", 
        "mp3_12" => "320kbps",                                                                                                       
        
        "status_0" => "None",                  
        "status_1" => "Confirm",                                                                                                       
        "status_2" => "Pass",                                                                                                       
        "status_3" => "Failure",                                                                                                       
        "status_4" => "Approval",                                                                                                       
        "status_5" => "Denial",                                                                                                       
        "status_6" => "On hold",                                                                                                       
               
        "new" => "New registration",  
        "create" => "Registration",
        "update" => "Update", 
        "edit" => "Edit",
        "delete" => "Delete", 
        "search" => "Search", 
        "back" => "Return", 
        "login" => "Log in",  
        "group" => "Group",  
        "account" => "Account",
        "user" => "User name : ",        
        "record" => "Recording",
        "upload" => "Upload", 
        "cancel" => "Cancel", 
        "info" => "Information", 
        "untitled" => "Untitled",    
        "statistics" => "Statistics",   
        "length" => "Length", 
        "filesize" => "File size",         
        "download" => "Download",                                     
    ],

];
