@extends('app')

@section('title', 'Create a new user')

@section('content')
    @foreach($errors->keys() as $messageName)
        <div class="alert alert-danger">

            <!-- outputs a '!' icon -->
            <span class="glyphicon glyphicon-exclamation-sign"></span>
            {{  $errors->first($messageName)  }}
        </div>
    @endforeach


<form method="post">
	{!! csrf_field() !!}

    <div class="form-group">
        <label for="inputName">First Name</label>
        <input name="first_name" class="form-control" id="inputName" placeholder="Joe" value="{{ $registerRequest['first_name'] }}">
    </div>

    <div class="form-group">
        <label for="inputName2">Last Name</label>
        <input name="last_name" class="form-control" id="inputName2" placeholder="Blow" value="{{ $registerRequest['last_name'] }}">
    </div>

  <div class="form-group">
    <label for="inputEmail">
      Email address
    </label>
    <input name="email" type="email" class="form-control" id="inputEmail" placeholder="Email" value="{{ $registerRequest['email'] }}">
  </div>

  <div class="form-group">
    <label for="inputPassword">Password</label>
    <input name="password" type="password" class="form-control" id="inputPassword" placeholder="Password">
  </div>

  <div class="form-group">
    <label for="inputPasswordRepeat">Confirm Password</label>
    <input name="password_confirmation" type="password" class="form-control" id="inputPassword" placeholder="Password">
  </div>

  <button type="submit" class="btn btn-default">Submit</button>
</form>

@endsection