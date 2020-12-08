@extends('layouts.app')

@section('title', trans('validation.attributes.form5'))

@section('content')
<p></p>
<h1>{{ trans('validation.attributes.form5') }}</h1>
<p></p>

{{-- エラーメッセージ --}}
@if (count($errors) > 0)
<div id="error_explanation" class="text-danger">
  <ul>
     @foreach ($errors->all() as $error)
       <li>{{ $error }}</li>
     @endforeach
  </ul>
</div>
@endif
<p></p>

{{-- 統計 --}}
<div class="alert alert-primary">{{ trans('validation.attributes.statistics') }}</div>
<p></p>
<table class="table table-hover">
  <tbody>
    <tr><td>{{ trans('validation.attributes.basics1') }}</td><td>{{ $count }}</td></tr>
    <tr><td>{{ trans('validation.attributes.basics2') }}</td><td>{{ $time }}</td></tr>
    <tr><td>{{ trans('validation.attributes.basics3') }}</td><td>{{ $size }}</td></tr>        
  </tbody>
</table>
<p></p>

{{-- フォーム --}} 
<form action="{{ url('basics') }}" method="post">
  @csrf
  
  <div class="alert alert-primary">1. {{ trans('validation.attributes.group') }}</div>
  <p></p>
  <div class="form-group">
    <label for="parent_g_name">{{ trans('validation.attributes.g_name') }} <span class="sp"><br></span><span style="color:green;">{{ trans('validation.attributes.msg_null') }}</span></label>
    @error('g_name')
      <input type="text" class="form-control is-invalid" id="parent_g_name" name="g_name" value="{{ old('g_name') }}">
    @else
      @if(isset($item) && is_null(old('_token')))
        <input type="text" class="form-control" id="parent_g_name" name="g_name" value="{{ $item->g_name }}">
      @else
        <input type="text" class="form-control" id="parent_g_name" name="g_name" value="{{ old('g_name') }}">
      @endif
    @enderror  
  </div>   
    
  <div class="form-group">
    <label for="parent_g_memo">{{ trans('validation.attributes.g_memo')}} <span class="sp"><br></span><span style="color:green;">{{ trans('validation.attributes.msg_null_plus') }}</span></label>
    @error('g_memo')
      <textarea rows="5" class="form-control is-invalid" id="parent_g_memo" name="g_memo">{{ old('g_memo') }}</textarea>
    @else
      @if(isset($item) && is_null(old('_token')))
        <textarea rows="5" class="form-control" id="parent_g_memo" name="g_memo">{{ $item->g_memo }}</textarea>
      @else
        <textarea rows="5" class="form-control" id="parent_g_memo" name="g_memo">{{ old('g_memo') }}</textarea>
      @endif      
    @enderror  
  </div>
  <div class="form-group">
    <label for="parent_g_info">{{ trans('validation.attributes.g_info')}} <span class="sp"><br></span><span style="color:green;">{{ trans('validation.attributes.msg_null_plus') }}</span></label>
    @error('g_info')
      <textarea rows="5" class="form-control is-invalid" id="parent_g_info" name="g_info">{{ old('g_info') }}</textarea>
    @else
      @if(isset($item) && is_null(old('_token')))
        <textarea rows="5" class="form-control" id="parent_g_info" name="g_info">{{ $item->g_info }}</textarea>
      @else
        <textarea rows="5" class="form-control" id="parent_g_info" name="g_info">{{ old('g_info') }}</textarea>
      @endif      
    @enderror  
  </div>
  
  @php
    if(isset($item) && is_null(old('_token'))){
      $val = $item->kbps;
    }else{
      $val = old('kbps');
    }     
  @endphp
  
  <div class="alert alert-primary">2. {{ trans('validation.attributes.mp3') }}</div>
  <p></p>
  <div class="form-group">
    <label for="parent_kbps">{{ trans('validation.attributes.kbps')}}</label>
    <select id="parent_kbps" class="form-control" name="kbps">      
      <option value= "1" {!! ($val ==  "1") ? 'selected="selected"' : '' !!}>{{ trans('validation.attributes.mp3_1')}}</option>
      <option value= "2" {!! ($val ==  "2") ? 'selected="selected"' : '' !!}>{{ trans('validation.attributes.mp3_2')}}</option>
      <option value= "3" {!! ($val ==  "3") ? 'selected="selected"' : '' !!}>{{ trans('validation.attributes.mp3_3')}}</option>
      <option value= "4" {!! ($val ==  "4") ? 'selected="selected"' : '' !!}>{{ trans('validation.attributes.mp3_4')}}</option>
      <option value= "5" {!! ($val ==  "5") ? 'selected="selected"' : '' !!}>{{ trans('validation.attributes.mp3_5')}}</option>
      <option value= "6" {!! ($val ==  "6") ? 'selected="selected"' : '' !!}>{{ trans('validation.attributes.mp3_6')}}</option>
      <option value= "7" {!! ($val ==  "7") ? 'selected="selected"' : '' !!}>{{ trans('validation.attributes.mp3_7')}}</option>
      <option value= "8" {!! ($val ==  "8") ? 'selected="selected"' : '' !!}>{{ trans('validation.attributes.mp3_8')}}</option>
      <option value= "9" {!! ($val ==  "9") ? 'selected="selected"' : '' !!}>{{ trans('validation.attributes.mp3_9')}}</option>
      <option value="10" {!! ($val == "10") ? 'selected="selected"' : '' !!}>{{ trans('validation.attributes.mp3_10')}}</option>
      <option value="11" {!! ($val == "11") ? 'selected="selected"' : '' !!}>{{ trans('validation.attributes.mp3_11')}}</option>
      <option value="12" {!! ($val == "12") ? 'selected="selected"' : '' !!}>{{ trans('validation.attributes.mp3_12')}}</option>
    </select>
  </div>   
  @if (app()->getLocale() == "ja")
    <p style="color:green;">{{ trans('validation.attributes.msg_mp3_info') }}<br>
    ※<a href="https://www.petitmonte.com/labo/voice-recording/">音声録音くん</a>でテスト録音してファイルをダウンロードできます。</p>
  @else
    <p style="color:green;">{{ trans('validation.attributes.msg_mp3_info') }}</p>
  @endif  
  
  <div class="alert alert-primary">3. {{ trans('validation.attributes.account') }}</div>
  <p></p>
  <div class="form-group">
    <label for="parent_name">{{ trans('validation.attributes.name') }}</label>
    @error('name')
      <input type="text" class="form-control is-invalid" id="parent_name" name="name" value="{{ old('name') }}">
    @else
      @if(isset($item) && is_null(old('_token')))
        <input type="text" class="form-control" id="parent_name" name="name" value="{{ $item->name }}">
      @else
        <input type="text" class="form-control" id="parent_name" name="name" value="{{ old('name') }}">
      @endif
    @enderror  
  </div>    
    
  <div class="form-group">
    <label for="parent_login_id">{{ trans('validation.attributes.login_id') }}</label>
    @error('login_id')
      <input type="text" class="form-control is-invalid" id="parent_login_id" name="login_id" value="{{ old('login_id') }}">
    @else
      @if(isset($item) && is_null(old('_token')))
        <input type="text" class="form-control" id="parent_login_id" name="login_id" value="{{ $item->login_id }}">
      @else
       <input type="text" class="form-control" id="parent_login_id" name="login_id" value="{{ old('login_id') }}">
      @endif      
    @enderror  
  </div>      

  <div class="form-group">
    <label for="parent_password">{{ trans('validation.attributes.password') }}</label>
    @error('password')
      <input type="password" class="form-control is-invalid" id="parent_password" name="password" value="">
    @else
      @if(isset($item) && is_null(old('_token')))
        <input type="password" class="form-control" id="parent_password" name="password" value="">
      @else
        <input type="password" class="form-control" id="parent_password" name="password" value="">
      @endif
    @enderror  
  </div>  
  
  <div class="form-group">
    <label for="parent_password_confirmation">{{ trans('validation.attributes.password_confirmation') }}</label>
    @error('password_confirmation')
      <input type="password" class="form-control is-invalid" id="parent_password_confirmation" name="password_confirmation" value="">
    @else
      @if(isset($item) && is_null(old('_token')))
        <input type="password" class="form-control" id="parent_password_confirmation" name="password_confirmation" value="">
      @else
        <input type="password" class="form-control" id="parent_password_confirmation" name="password_confirmation" value="">
      @endif
    @enderror  
  </div>    
  <p style="color:green;">{{ trans('validation.attributes.msg_password') }}<br><br></p>

  <input type="submit" value="{{ trans('validation.attributes.update') }}" class="btn btn-primary">    
  <p></p>
  <br>
</form>
@endsection
