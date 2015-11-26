@extends('app')

@section('title', 'Overview of '.$user->first_name)

@section('content')
    Name: {{$user->name}}<br />
    Tickets:
    @forelse($tickets as $ticket)
         <br /><a href="/ticket/{{$ticket->id}}">{{$ticket->subject}}</a>
    @empty
    	<span style="color:red">No tickets</span>
    @endforelse
    @can('edit-roles')
    	<hr />
    	<h2> Roles </h2>
    	@foreach($roles as $role)
    		<form method="post" action="/user/editRoles/{{$user->id}}">
    			{{ csrf_field() }}
    			@if($user->is($role->description))
    				<input type="submit" value="Remove" id="{{$role->description}}Role" name="{{$role->description}}Role">
    			@else
    				<input type="submit" value="Add" id="{{$role->description}}Role" name="{{$role->description}}Role">
    			@endif
    			<label for="{{$role->description}}Role">{{ $role->description }}</label>
    		</form>
    	@endforeach
    @endcan
    <br />
@endsection