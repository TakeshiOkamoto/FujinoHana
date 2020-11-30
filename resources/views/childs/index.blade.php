@extends('layouts.app')

@section('title', trans('validation.attributes.form6'))

@section('content')
<p></p>
<h1>{{ trans('validation.attributes.form6') }}</h1>
<p></p>

<form action="{{ url('childs') }}" method="get">
  <div class="input-group">
    <input type="search" name="name" class="form-control" placeholder="{{ trans('validation.attributes.msg_name_search') }}" value="{{ $name }}">
    <span class="input-group-btn">
      <input type="submit" value="{{ trans('validation.attributes.search') }}" class="btn btn-outline-info"> 
    </span>
  </div>
</form>

<p></p>

<table class="table table-hover">
  <thead class="thead-default">
    <tr>
      <th>{{ trans('validation.attributes.name') }}</th>
      <th class="pc">{{ trans('validation.attributes.login_id') }}</th>
      <th></th>  
    </tr>
  </thead>
  <tbody class="thead-default">
    @foreach ($items as $item)
    <tr>
      <td><a href="{{ url('childs/' . $item->id) }}">{{ $item->name }}</a></td>
      <td class="pc">{{ $item->login_id }}</td>
      <td style="width:170px;">
        <a href="{{ url('childs/' . $item->id . '/edit') }}" class="btn btn-primary">{{ trans('validation.attributes.edit') }}</a>
        &nbsp;&nbsp;
        @if (app()->getLocale() == "ja")
          <a href="#" onclick="ajax_delete('「{{ $item->name }}」を削除します。よろしいですか？','{{ url('childs/' . $item->id) }}','{{ url('childs') }}');return false;" class="btn btn-danger">{{ trans('validation.attributes.delete') }}</a>
        @else 
          <a href="#" onclick="ajax_delete('Delete {{ $item->name }}. Is it OK?','{{ url('childs/' . $item->id) }}','{{ url('childs') }}');return false;" class="btn btn-danger">{{ trans('validation.attributes.delete') }}</a>
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
<a href="{{ url('childs/create') }}" class="btn btn-primary">{{ trans('validation.attributes.new') }}</a>
<p><br></p>
@endsection