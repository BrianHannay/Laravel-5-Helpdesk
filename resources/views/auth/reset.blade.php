@extends('app')

@section('title', 'Reset your password')

@section('content')



<!-- resources/views/auth/reset.blade.php -->

<form method="POST" action="/password/reset">
    {!! csrf_field() !!}
    <input type="hidden" name="token" value="{{ $token }}">


    @if (count($errors) > 0)
        <ul>
            @foreach ($errors->all() as $error)
            <div class="alert alert-danger">
              <span class="glyphicon glyphicon-exclamation-sign"></span>
              {{ $error }}
            </div>
            @endforeach
        </ul>
    @endif

    <div>
        Email
        <input type="email" name="email" value="{{ old('email') }}">
    </div>

    <div>
        Password
        <input type="password" name="password">
    </div>

    <div>
        Confirm Password
        <input type="password" name="password_confirmation">
    </div>

    <div>
        <button type="submit">
            Reset Password
        </button>
    </div>
</form>

@endsection