<!DOCTYPE html>
<html lang="en">
<head>
	<title>@yield('title') at L5_Ticket</title>

	<link rel="stylesheet" href="/styles/app.css">
	
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <meta charset="utf-8"> 


</head>
<body>

	<div class="jumbotron text-center" id="jumbo">
		<h4><strong style="color: white">VIU Help Desk</strong></h4>
	</div>
	@include('nav')
	<div class="container" style="margin-top:10px">
		@yield('content')
	</div>
	<hr />
	<footer class="container text-center">
	</footer>
</body>
</html>

