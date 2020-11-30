@extends('layouts.app')

@section('title', trans('validation.attributes.form6'))

@section('content')
<p></p>
<h1>{{ trans('validation.attributes.new') }}</h1>
<p></p>

@include('childs._form', ['form_action' => url('childs')])

<p></p>
<a href="{{ url('childs') }}">{{ trans('validation.attributes.back') }}</a>
<p></p>
@endsection