@extends('layouts.app')

@section('title', trans('validation.attributes.form6'))

@section('content')
<p></p>
<h1>{{$item->name}}</h1>
<p></p>

<p>
  <strong>{{trans('validation.attributes.name')}} : </strong>
  {{$item->name}}
</p>

<p>
  <strong>{{trans('validation.attributes.login_id')}} : </strong>
  {{$item->login_id}}
</p>

<p>
  <strong>{{trans('validation.attributes.created_at')}} : </strong>
  {{$item->created_at}}
</p>

<p>
  <strong>{{trans('validation.attributes.updated_at')}} : </strong>
  {{$item->updated_at}}
</p>


<a href="{{ url('childs/' . $item->id . '/edit')}}">{{ trans('validation.attributes.edit') }}</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="{{ url('childs')}}">{{ trans('validation.attributes.back') }}</a>
<p></p>

@endsection
