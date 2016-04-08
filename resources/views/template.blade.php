<!DOCTYPE html>
<html lang="en">
<head>
	@include("header")
</head>
	<body>

		 @if(Auth::check())
			@include("nav")
         @endif
		<br> <br>
		<div class="container">
			<div style="margin: 0 30px;">
				@yield("content")
			</div>
		</div>
		@include("footer")
	</body>

	
</html>