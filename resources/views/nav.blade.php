<nav class="text-center clearfix">
	<a href="/" class="col-xs-3">Home</a>
	<a href="/ticket/submit" class="col-xs-3">Submit Ticket</a>
	<div class="col-xs-6">
		@if(Auth::check())			
			<a href="/user/me" class="col-xs-6">
				{{Auth::user()->name}}
			</a>
			<a href="/auth/logout" class="col-xs-6">Log out</a>
		@else
			<a href="/auth/login" class="col-xs-6">Log in</a>
			<a href="/auth/signup" class="col-xs-6">Sign up</a>
		@endif

	</div>
</nav> 