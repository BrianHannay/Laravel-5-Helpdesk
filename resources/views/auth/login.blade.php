@extends('app')

@section('title', 'Login')

@section('content')
<h2>Login</h2>
<form method="post">
  {!! csrf_field() !!}
  @if(Session::has('errors') and Session::get('errors')->has('email'))
    <div class="alert alert-danger">
      <span class="glyphicon glyphicon-exclamation-sign"></span>
      &nbsp;{{Session::get('errors')->first('email')}}
    </div>
  @endif
  <div class="form-group">
    <label for="inputEmail">Email address</label>
    <input name="email" type="email" class="form-control" id="inputEmail" placeholder="Email" value="{{ old('email') }}">
  </div>

  @if(Session::has('errors') and Session::get('errors')->has('password'))
    <div class="alert alert-danger">
      <span class="glyphicon glyphicon-exclamation-sign"></span>
      &nbsp;{{Session::get('errors')->first('password')}}
    </div>
  @endif
  <div class="form-group">
    <label for="inputPassword">Password</label>
    <input name="password" type="password" class="form-control" id="inputPassword" placeholder="Password"><br />
    <a href="/password/email">Forgot your password?</a><br />
  </div>
  <button type="submit" class="btn btn-default">Submit</button>
  <input type="checkbox" name="remember"> Remember Me

</form>

@endsection