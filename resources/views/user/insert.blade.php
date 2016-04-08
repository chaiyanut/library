@extends("template")
@section("content")

<div class="panel panel-default">
	  <div class="panel-heading">
	  <legend>Insert Data</legend>
	  	<div class="col-md-4">
			<form action="" method="POST" role="form">
				<div class="form-group">
                    @if (Session::has('message') && Session::has('alert'))
                    	
                        <div class="alert alert-{{Session::get('alert')}}" role="alert">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{Session::get('message')}}
                        </div>
                    @endif
                </div>
				<div class="form-group"> <br>
				
				<label for="">code</label>
				
					<input type="text" class="form-control" name="code" required>
					<br>

					<label for="">name</label>
					<input type="text" class="form-control" name="name" required>
					<br>

					<label for="">year</label>
					<input type="text" class="form-control" name="year" required>
					<br>

					<label for="">ISBN</label>
					<input type="text" class="form-control" name="isbn" required>
					<br>

					<label for="">Category</label>
					<select class="form-control" name="category">
						<option value="Computer System">Computer System</option>
						<option value="General" selected="">General</option>
						<option value="Information">Information</option>
						<option value="Information">Network And Internet</option>
						<option value="Robotic">Robotic</option>
						<option value="other">Other</option>
						<option value="Project Report">Project Report</option>
						<option value="Journal and magazine">Journal and magazine</option>
						
					</select>
					<br>
					<label for="">author</label>
					<input type="text" class="form-control" name="author" required>
					<br>

					<label for="">edition</label>
					<input type="text" class="form-control" name="edition" required>
					<br>

					<label for="">publisher</label>
					<input type="text" class="form-control" name="publisher" required>
					<br>

				</div>
			
				{!! csrf_field() !!}
				<div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <div class="pull-right">
              	<a href="{{url('search')}}" class="btn btn-default">Cancel</a> 
				<button type="submit" class="btn btn-primary">Save</button>
			</div>
			</div>
			</form>
		</div>
	  </div>
	  
	  <div class="panel-body">


	 </div>
</div>
@endsection