@extends('app')
@section('title', 'Search tickets')

@section('content')
    <form method="post" action="/search/search" class="container">
        {!! csrf_field() !!}
        <div class="input-group">
            <input class="form-control" name="query" placeholder="Search Tickets" type="search" />
			<span class="input-group-btn">
				<button class="btn btn-default form-control">
                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search
                </button>
			</span>
        </div>
    </form>
@endsection