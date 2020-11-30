@extends('layouts.app')

@section('title',  trans('validation.attributes.form3'))

@section('content')
<p></p>
<h1>{{ trans('validation.attributes.form3') }}</h1>
<p></p>

<form action="{{ url('childfiles') }}" method="get">
  <div class="input-group">
    <input type="search" name="name" class="form-control" placeholder="{{ trans('validation.attributes.msg_name_search') }}" value="{{ $name }}">
    <span class="input-group-btn">
      <input type="submit" value="{{ trans('validation.attributes.search') }}" class="btn btn-outline-info"> 
    </span>
  </div>
</form>

<p></p>

<table class="table table-hover pc">
  <thead class="thead-default">
    <tr>
      <th style="width: 65px;">{{ trans('validation.attributes.status') }}</th>
      <th>{{ trans('validation.attributes.title') }}</th>  
      <th>{{ trans('validation.attributes.created_at') }}</th> 
      <th>{{ trans('validation.attributes.length') }}</th>          
      <th style="width:170px;"></th>  
    </tr>
  </thead>
  <tbody class="thead-default">
    @foreach ($items as $item)
    <tr>
      <td>{!! $item->status_raw !!}</td>  
      <td><a href="{{ url('childfiles/' . $item->id) }}">{{ $item->title }}</a><br><span class="text-muted" style="font-size:90%">{{ $item->name }}</span></td>
      <td>{{ $item->created_at }}</td>
      <td>{{ $item->filetime }}</td>
      <td style="width:170px;">
        <a href="{{ url('childfiles/' . $item->id . '/edit') }}" class="btn btn-primary">{{ trans('validation.attributes.edit') }}</a>
        &nbsp;&nbsp;
        @if (app()->getLocale() == "ja")
          <a href="#" onclick="ajax_delete('「{{ $item->title }}」を削除します。よろしいですか？','{{ url('childfiles/' . $item->id) }}','{{ url('childfiles') }}');return false;" class="btn btn-danger">{{ trans('validation.attributes.delete') }}</a>
        @else 
          <a href="#" onclick="ajax_delete('Delete {{ $item->title }}. Is it OK?','{{ url('childfiles/' . $item->id) }}','{{ url('childfiles') }}');return false;" class="btn btn-danger">{{ trans('validation.attributes.delete') }}</a>
        @endif
      </td>            
    </tr>    
    @endforeach
  </tbody>    
</table>

<table class="table table-hover sp">
  <thead class="thead-default">
    <tr>
      <th style="width: 65px;">{{ trans('validation.attributes.title') }}</th>
    </tr>
  </thead>
  <tbody class="thead-default">
    @foreach ($items as $item)
    <tr>
      <td>
       {!! $item->status_raw !!} <a href="{{ url('childfiles/' . $item->id) }}">{{ $item->title }}</a><br>
       <span class="text-muted" style="font-size:90%">{{ $item->name }}</span><br>       
       {{ $item->created_at }}<br>
       ({{ $item->filetime }})<br> 
       <div style="margin-top:5px;"></div>
       <a href="{{ url('childfiles/' . $item->id . '/edit') }}" class="btn btn-primary">{{ trans('validation.attributes.edit') }}</a>
       &nbsp;&nbsp;

       @if (app()->getLocale() == "ja")
         <a href="#" onclick="ajax_delete('「{{ $item->title }}」を削除します。よろしいですか？','{{ url('childfiles/' . $item->id) }}','{{ url('childfiles') }}');return false;" class="btn btn-danger">{{ trans('validation.attributes.delete') }}</a>
       @else 
         <a href="#" onclick="ajax_delete('Delete {{ $item->title }}. Is it OK?','{{ url('childfiles/' . $item->id) }}','{{ url('childfiles') }}');return false;" class="btn btn-danger">{{ trans('validation.attributes.delete') }}</a>
       @endif
      </td>    
    </tr>    
    @endforeach
  </tbody>    
</table>

{{ $items->appends(['name' => $name])->links() }}

@if (count($items) >0)
  @if (app()->getLocale() == "ja")
    <p>全{{ $items->total() }}件中 
         {{  ($items->currentPage() -1) * $items->perPage() + 1 }} - 
         {{ (($items->currentPage() -1) * $items->perPage() + 1) + (count($items) -1) }}件<span class="pc">のデータ</span>が表示されています。</p>
  @else
    <p>Displaying data <b>{{  ($items->currentPage() -1) * $items->perPage() + 1 }} - 
      {{ (($items->currentPage() -1) * $items->perPage() + 1) + (count($items) -1) }}</b> of <b>{{ $items->total() }}</b> in total</p>
  @endif     
@else
  <p>{{ trans('validation.attributes.msg_none') }}</p>
@endif 

<p></p>
<p><br></p>
@endsection