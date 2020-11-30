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

{{-- フォーム --}} 
<form action="{{ $form_action }}" method="post">
  @csrf
  
  {{-- 初期表示(編集) --}}
  @if(isset($item) && is_null(old('_token')))  
    <input type="hidden" name="id" value="{{ $item->id }}">
  {{-- 新規/編集 --}}    
  @else
    <input type="hidden" name="id" value="{{ old('id') }}">
  @endif   
      
  <div class="form-group">
    <label for="child_name">{{ trans('validation.attributes.name') }}</label>
    @error('name')
      <input type="text" class="form-control is-invalid" id="child_name" name="name" value="{{ old('name') }}">
    @else
      @if(isset($item) && is_null(old('_token')))
        <input type="text" class="form-control" id="child_name" name="name" value="{{ $item->name }}">
      @else
        <input type="text" class="form-control" id="child_name" name="name" value="{{ old('name') }}">
      @endif
    @enderror  
  </div>    
  
  {{-- Create --}} 
  @if ($form_action == url('childs'))
    <div class="form-group">
      <label for="child_login_id">{{ trans('validation.attributes.login_id') }}</label>
      @error('login_id')
        <input type="text" class="form-control is-invalid" id="child_login_id" name="login_id" value="{{ old('login_id') }}">
      @else
        @if(isset($item) && is_null(old('_token')))
          <input type="text" class="form-control" id="child_login_id" name="login_id" value="{{ $item->login_id }}">
        @else
         <input type="text" class="form-control" id="child_login_id" name="login_id" value="{{ old('login_id') }}">
        @endif      
      @enderror  
    </div>
  {{-- Edit --}}  
  @else
    <div class="form-group">
      <label for="child_login_id">{{ trans('validation.attributes.login_id') }}</label>
      @if(isset($item) && is_null(old('_token')))
        <input type="text" class="form-control" id="child_login_id" value="{{ $item->login_id }}" disabled="disabled" >
        <input type="hidden" class="form-control" id="child_login_id" name="login_id" value="{{ $item->login_id }}">
      @else
        <input type="text" class="form-control" id="child_login_id" value="{{ old('login_id') }}" disabled="disabled" >    
        <input type="hidden" class="form-control" id="child_login_id" name="login_id" value="{{ old('login_id') }}">
      @endif      
    </div>   
  @endif        

  <div class="form-group">
    <label for="child_password">{{ trans('validation.attributes.password') }}</label>
    @error('password')
      <input type="password" class="form-control is-invalid" id="child_password" name="password" value="">
    @else
      @if(isset($item) && is_null(old('_token')))
        <input type="password" class="form-control" id="child_password" name="password" value="">
      @else
        <input type="password" class="form-control" id="child_password" name="password" value="">
      @endif
    @enderror  
  </div>  
  
  <div class="form-group">
    <label for="child_password_confirmation">{{ trans('validation.attributes.password_confirmation') }}</label>
    @error('password_confirmation')
      <input type="password" class="form-control is-invalid" id="child_password_confirmation" name="password_confirmation" value="">
    @else
      @if(isset($item) && is_null(old('_token')))
        <input type="password" class="form-control" id="child_password_confirmation" name="password_confirmation" value="">
      @else
        <input type="password" class="form-control" id="child_password_confirmation" name="password_confirmation" value="">
      @endif
    @enderror  
  </div>      
  <p></p>  
  
  @if(isset($item))
    <p style="color:green;">{{ trans('validation.attributes.msg_password') }}<br><br></p>
    <input type="hidden" name="_method" value="PUT">
    <input type="submit" value="{{ trans('validation.attributes.update') }}" class="btn btn-primary">    
  @else
    <input type="submit" value="{{ trans('validation.attributes.create') }}" class="btn btn-primary">    
  @endif
</form>
