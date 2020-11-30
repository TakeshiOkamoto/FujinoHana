@extends('layouts.app')

@section('title', trans('validation.attributes.form8'))

@section('content')
<p></p>
<h1>{{ trans('validation.attributes.form8') }}</h1>
<p></p>

{{-- エラーメッセージ --}}
@if (isset($login_error))
  <div id="error_explanation" class="text-danger">
    <ul>
      <li>{{ trans('validation.attributes.msg_login_error') }}</li>
    </ul>
  </div>
@endif
<p></p>

{{-- フォーム --}}
<form action="{{ url('login') }}" method="post">
  @csrf  
  <div class="form-group">
    <label for="user_login_id">{{ trans('validation.attributes.login_id') }}</label>
    <input type="text" class="form-control" id="user_login_id" name="login_id">
  </div>     
  <div class="form-group">
    <label for="user_password">{{ trans('validation.attributes.password') }}</label>
    <input type="password" class="form-control" id="user_password" name="password">
  </div>     
  <input type="submit" value="{{ trans('validation.attributes.login') }}" class="btn btn-primary">  
</form>  
<p><br></p>
@endsection