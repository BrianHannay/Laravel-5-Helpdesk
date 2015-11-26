@extends('app')

@section('title', 'Reset your password')

@section('content')

    <!-- resources/views/auth/password.blade.php -->

    <form method="POST" action="/password/email">
        {!! csrf_field() !!}

        @if (count($errors) > 0)
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <div>
            Enter the email you use to login<br />
            <input type="email" name="email" value="{{ old('email') }}">
        </div>
        <div>
            <button type="submit">
                Send Password Reset Link
            </button>
        </div>
    </form>

@endsection