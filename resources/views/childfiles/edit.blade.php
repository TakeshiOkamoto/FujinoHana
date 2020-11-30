@extends('layouts.app')

@section('title', $item->title)

@section('content')
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

{{-- フォーム --}} 
<form action="{{ url('childfiles/' . $item->id) }}" method="post">
  @csrf

  <p></p>
  <div class="form-group">
    <label for="file_title">{{ trans('validation.attributes.title') }}</label>
    @error('title')
      <input type="text" class="form-control is-invalid" id="file_title" name="title" value="{{ old('title') }}">
    @else
      @if(isset($item) && is_null(old('_token')))
        <input type="text" class="form-control" id="file_title" name="title" value="{{ $item->title }}">
      @else
        <input type="text" class="form-control" id="file_title" name="title" value="{{ old('title') }}">
      @endif
    @enderror  
  </div>       
  <div class="form-group">
    <label for="file_c_memo">{{ trans('validation.attributes.c_memo')}} ({{ $item->name }})<br><span style="color:green;">{{ trans('validation.attributes.msg_null_plus') }}</span></label>
    @error('c_memo')
      <textarea rows="5" class="form-control is-invalid" id="file_c_memo" name="c_memo">{{ old('c_memo') }}</textarea>
    @else
      @if(isset($item) && is_null(old('_token')))
        <textarea rows="5" class="form-control" id="file_c_memo" name="c_memo">{{ $item->c_memo }}</textarea>
      @else
        <textarea rows="5" class="form-control" id="file_c_memo" name="c_memo">{{ old('c_memo') }}</textarea>
      @endif      
    @enderror  
  </div>
  <div class="form-group">
    <label for="file_p_memo">{{ trans('validation.attributes.p_memo')}}  ({{session('name') }})<br><span style="color:green;">{{ trans('validation.attributes.msg_null_plus') }}</span></label>
    @error('p_memo')
      <textarea rows="5" class="form-control is-invalid" id="file_p_memo" name="p_memo">{{ old('p_memo') }}</textarea>
    @else
      @if(isset($item) && is_null(old('_token')))
        <textarea rows="5" class="form-control" id="file_p_memo" name="p_memo">{{ $item->p_memo }}</textarea>
      @else
        <textarea rows="5" class="form-control" id="file_p_memo" name="p_memo">{{ old('p_memo') }}</textarea>
      @endif      
    @enderror  
  </div>  
  
  <div class="form-group">
    <label for="file_status">{{ trans('validation.attributes.status')}}</label>
    <select id="file_status" class="form-control" name="status">      
      <option value= "0" {{ ($item->status ==  "0") ? 'selected="selected"' : '' }}>{{ trans('validation.attributes.status_0')}}</option>
      <option value= "1" {{ ($item->status ==  "1") ? 'selected="selected"' : '' }}>{{ trans('validation.attributes.status_1')}}</option>
      <option value= "2" {{ ($item->status ==  "2") ? 'selected="selected"' : '' }}>{{ trans('validation.attributes.status_2')}}</option>
      <option value= "3" {{ ($item->status ==  "3") ? 'selected="selected"' : '' }}>{{ trans('validation.attributes.status_3')}}</option>
      <option value= "4" {{ ($item->status ==  "4") ? 'selected="selected"' : '' }}>{{ trans('validation.attributes.status_4')}}</option>
      <option value= "5" {{ ($item->status ==  "5") ? 'selected="selected"' : '' }}>{{ trans('validation.attributes.status_5')}}</option>
      <option value= "6" {{ ($item->status ==  "6") ? 'selected="selected"' : '' }}>{{ trans('validation.attributes.status_6')}}</option>
    </select>
  </div>   
  
  <input type="hidden" name="_method" value="PUT">
  <input type="submit" value="{{ trans('validation.attributes.update') }}" class="btn btn-primary">    
  <p></p>
  <p></p>
  <a href="{{ url('childfiles') }}">{{ trans('validation.attributes.back') }}</a>
  <p></p>
</form>
@endsection
