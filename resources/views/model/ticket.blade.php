<h2>{{$ticket->subject}}</h2>
Status: {{ucfirst($ticket->friendlyStatus())}} <br />

Assigned to:
@if(isset($ticket->assignedTo->first_name))
    {{ $ticket->assignedTo->first_name }}
@else
    Nobody
@endif
<hr>

@foreach($ticket->messages as $message)
    <div class="xs-col-12 alternateBackground indent">
    	@if($message->user)
        	<a href="/user/{{ $message->user->id }}">{{ $message->user->name }}</a> says:<br />
        @endif
        {!! nl2br(htmlentities($message->text)) !!}
    </div>
@endforeach
@if(!$ticket->messages->isEmpty())
<hr />
@endif

@can('add-message-to-ticket', $ticket)
    <form method="post" action="/ticket/message/{{$ticket->id}}">
        {{ csrf_field() }}

		@can('change-ticket-status', $ticket)
		<label for="statusSelecter">Set status to</label><br />
	    <select name="newStatus" id="statusSelecter">
	        @for($i=0;$i <= 5; $i++)
	            <option value="{{$i}}" {{ $i == $ticket->status ? "selected":"" }}>{{ucfirst($ticket->friendlyStatus($i))}}</option>
	        @endfor
	    </select>
	    <br />
	    @endcan

	    <label for="message">Reply to this ticket</label><br />
        <textarea id="message" name="message" class="col-xs-12"></textarea><br />
        <input type="submit" value="Reply">
    
    </form>
@endcan