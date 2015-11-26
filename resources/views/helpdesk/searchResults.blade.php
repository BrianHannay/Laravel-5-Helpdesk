@extends('app')
@section('title', 'Search results')

@section('content')
@foreach($tickets as $ticket)
    <div class="xs-col-10">
        <a href="ticket/{{$ticket->id}}">
            $ticket->subject;
        </a>
    </div>

@endforeach
@endsection