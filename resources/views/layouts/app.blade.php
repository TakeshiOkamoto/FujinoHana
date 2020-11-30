<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="UTF-8">
<title>@yield('title')</title>
<meta name="robots" content="noindex, nofollow">
<meta name="keywords" content="@yield('keywords')">
<meta name="description" content="@yield('description')">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" media="all" href="{{url('css/bootstrap.min.css')}}">
<link rel="stylesheet" media="all" href="{{url('css/terminal.css')}}">
<script src="{{url('js/common.js')}}"></script>
@if (isset($home))
<script src ="{{url('js/voice-recording.js')}}"></script>
@endif
</head>
<body>

{{-- ヘッダ --}}
<nav class="navbar navbar-expand-md navbar-light bg-primary">
  <div class="navbar-brand text-white">
    {{ trans('validation.attributes.app_name') }}
  </div>
    @if (session()->has('name'))
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" style="color:#fff;" href="{{ url('/') }}">{{ trans('validation.attributes.form1') }}</a>
        </li>
        @if (session()->has('parentID'))
          <li class="nav-item">
            <a class="nav-link" style="color:#fff;" href="{{ url('files') }}">{{ trans('validation.attributes.form2') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" style="color:#fff;" href="{{ url('childfiles') }}">{{ trans('validation.attributes.form3') }}</a>
          </li>
        @else
          <li class="nav-item">
            <a class="nav-link" style="color:#fff;" href="{{ url('files') }}">{{ trans('validation.attributes.form4') }}</a>
          </li>
        @endif

        @if (session()->has('parentID'))
          <li class="nav-item">
            <a class="nav-link" style="color:#fff;" href="{{ url('basics') }}">{{ trans('validation.attributes.form5') }}</a>
          </li>    
          <li class="nav-item">
            <a class="nav-link" style="color:#fff;" href="{{ url('childs') }}">{{ trans('validation.attributes.form6') }}</a>
          </li> 
        @endif                   
        <li class="nav-item">
          <a class="nav-link" style="color:#fff;" href="{{ url('logout') }}">{{ trans('validation.attributes.form7') }}</a>
        </li>
      </ul>
    @endif    
</nav>

<div class="container">

  {{-- フラッシュ --}}
  @if(session()->has('flash_msg'))
    @if (session('flash_flg') === 1)
      <div class="alert alert-success" id="msg_notice">{{session('flash_msg')}}</div>
    @endif
    @if (session('flash_flg') === 0)
      <div class="alert alert-danger" id="msg_alert">{{session('flash_msg')}}</div>  
    @endif
    {{ session()->forget('flash_msg')}}
    {{ session()->forget('flash_flg')}}    
  @endif  
  
  {{-- メイン --}}
  <div>
    @yield('content')
  </div>
  
  {{-- フッタ --}}
  <nav class="container bg-primary p-2 text-center">
    <div class="text-center text-white">
      {{ trans('validation.attributes.app_name') }}<br>
      Copyright 2020 <a href="https://github.com/TakeshiOkamoto/FujinoHana" style="color:white;">Takeshi Okamoto</a> All Rights Reserved.
    </div>
  </nav>   
</div>
</body>
</html>
