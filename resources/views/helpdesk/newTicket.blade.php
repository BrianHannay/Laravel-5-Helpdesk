@extends('app')
@section('title', 'Create ticket')

@section('content')
    <form method="post" action="/ticket/submit" class="container">
        {!! csrf_field() !!}
        <div class="input-group">
            <label for="ticketTitle"></label><br />
            <input id="ticketTitle" class="form-control" name="ticketTitle" placeholder="Title" />
        </div>
        <div class="input-group" style="width: 100%;">
            <label for="ticketText"></label><br />
            <textarea id="ticketText" class="form-control" name="ticketText" placeholder="Ticket text" rows="12" /></textarea>
        </div>
        <input type="submit" value="Submit Ticket" />
    </form>
@endsection