@extends('layouts.app')

@section('title', trans('validation.attributes.app_name'))
@section('keywords', '')
@section('description', '')

@section('content')
<p></p>

{{-- グループ名 --}}
@if ($item->g_name != "")
<h3>{{ $item->g_name }}</h3>
<p>{{ trans('validation.attributes.user') }}{{ session('name') }}</p> 
@else
<div style="margin-top:30px;"></div>
@endif

{{-- グループメモ --}}
<p>{!! $g_memo !!}  </p>

{{-- タイトル --}}
<div class="input-group mb-2">
  <input type="text" class="form-control col-sm-5" id="file_title">
  <div class="input-group-prepend">
    <span class="input-group-text">{{ trans('validation.attributes.title') }}</span>
  </div>
</div>
<p></p>

{{-- ボタン --}}
<button type="button" id="btn_record" onclick="record();" class="btn btn-info btn-lg"> {{ trans('validation.attributes.record') }} </button>&nbsp;&nbsp;
<button type="button" id="btn_stop" onclick="stop();" class="btn btn-outline-info btn-lg" disabled="disabled">{{ trans('validation.attributes.upload') }}</button>
<p></p>

{{-- プログレスバー --}}
<div id="progress_bar" style="display:none;">
  <img src="images/loading.gif">&nbsp;
  <span style="vertical-align:middle;" id="lbl_processing">{{ trans('validation.attributes.msg_mp3_recording') }}</span>&nbsp;&nbsp;
  <span id="lbl_progress"  style="vertical-align:middle;"></span>
</div>
<p></p>

{{-- 警告 --}}
<script>  
  var userAgent = window.navigator.userAgent.toLowerCase();
  if(userAgent.indexOf('msie') != -1 || userAgent.indexOf('trident') != -1) {            
    document.write('<p style="color:red;">{{ trans('validation.attributes.msg_alert1') }}<br>{{ trans('validation.attributes.msg_alert2') }}</p>');
  }         
</script>

{{-- キャンバス --}}
<canvas id="MyCanvas" style="border:1px solid #ccc;" width="300" height="200" ></canvas>
<br>

{{-- キャンセル --}}
<div id="btn_cancel" style="display:none;width:300px;margin-bottom:8px;" class="clearfix">
  <div class="float-left">
    <a href="{{ url('/') }}">{{ trans('validation.attributes.cancel') }}</a>
  </div>
  <div class="float-right" style="margin-right:5px;" id="lbl_timer">00:00:00</div>  
</div>  
<p class="text-muted pc" >{{ trans('validation.attributes.msg_browser_pc') }}</p>
<p class="text-muted sp" >{{ trans('validation.attributes.msg_browser_sp') }}</p>

@if ($item->g_info != "")
  <div class="card-group">                    
    <div class="card"> 
      <div class="card-header bg-primary text-light">
        {{ trans('validation.attributes.info') }}
      </div>
      <div class="card-body">
        {!! $g_info !!}              
      </div>    
    </div>
  </div>  
  <p></p>
@endif

{{-- PHP/JS連携 --}}
<input type="hidden" id="kbps" value="{{ $item->kbps }}">
<input type="hidden" id="untitled" value="{{ trans('validation.attributes.untitled') }}">
<input type="hidden" id="msg_mp3_recording" value="{{ trans('validation.attributes.msg_mp3_recording') }}">
<input type="hidden" id="msg_mp3_upload" value="{{ trans('validation.attributes.msg_mp3_upload') }}">
<input type="hidden" id="msg_microphone" value="{{ trans('validation.attributes.msg_microphone') }}">
<input type="hidden" id="msg_error_413" value="{{ trans('validation.attributes.msg_error_413') }}">
<input type="hidden" id="msg_error_419" value="{{ trans('validation.attributes.msg_error_419') }}">
<input type="hidden" id="msg_error_500" value="{{ trans('validation.attributes.msg_error_500') }}">
@endsection
