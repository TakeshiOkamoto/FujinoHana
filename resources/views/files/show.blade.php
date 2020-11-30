@extends('layouts.app')

@section('title', $item->title)

@section('content')
<p></p>
<h1>{{$item->title}}</h1>
{!! $item->status_raw !!}
<p></p>

<br>
<audio controls src="{{ url('download?f=' . $item->filename ) }}" ></audio>
<br>

<br>
<p>
  @if(session()->has('childID')) 
    <strong>{{trans('validation.attributes.c_memo')}} ({{ session('name') }}): </strong><br>
  @else
    <strong>{{trans('validation.attributes.c_memo')}} : </strong><br>
  @endif
  {!! $item->c_memo !!}
</p>

@if(session()->has('childID'))
  <p>
    <strong>{{trans('validation.attributes.p_memo')}} ({{ $parent_item->name }}) : </strong><br>
    {!! $item->p_memo !!}
  </p>
@endif

<p>
  <strong>{{trans('validation.attributes.download')}} : </strong>
  <a href="{{ url('download?f=' . $item->filename ) }}">{{ $item->filename }}</a>
</p>

<p>
  <strong>{{trans('validation.attributes.length')}} : </strong>
  {{$item->filetime}}
</p>

<p>
  <strong>{{trans('validation.attributes.filesize')}} : </strong>
  {{$item->filesize}}
</p>

<p>
  <strong>{{trans('validation.attributes.created_at')}} : </strong>
  {{$item->created_at}}
</p>

<p>
  <strong>{{trans('validation.attributes.updated_at')}} : </strong>
  {{$item->updated_at}}
</p>

@if ((session()->has('parentID')) || (session()->has('childID') && $item->status == 0))
<a href="{{ url('files/' . $item->id . '/edit')}}">{{ trans('validation.attributes.edit') }}</a>&nbsp;&nbsp;|&nbsp;
@endif
<a href="{{ url('files')}}">{{ trans('validation.attributes.back') }}</a>
<p></p>
@endsection
