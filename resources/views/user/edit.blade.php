@extends("template")
@section("content")

<div class="panel panel-default">
	  <div class="panel-heading">
	  <legend>Edit Data</legend>
	  	<div class="col-md-4">
			<form action="" method="POST" role="form">

				<div class="form-group"> <br>
				
				<label for="">code</label>
				
					<input type="text" class="form-control" name="code" value="{{$book->code}}">
					<br>

					<label for="">name</label>
					<input type="text" class="form-control" name="name" value="{{$book->name}}">
					<br>

					<label for="">year</label>
					<input type="text" class="form-control" name="year" value="{{$book->year}}">
					<br>

					<label for="">ISBN</label>
					<input type="text" class="form-control" name="ISBN" value="{{$book->isbn}}">
					<br>

					<label for="sel1">Category:</label>
					<select class="form-control" name="category">
					<?php

						$cats = array('Computer System', 'Information','Network And Internet', 'Robotic','Other', 'Project Report', 'Journal and magazine', );
						foreach ( $cats as $cat ) {
							if ( $book->category == $cat )
								$selected = ' selected=""';
							else
								$selected = "";
							echo '<option'. $selected. ' value="'. $cat.'">'. ucfirst($cat). '</option>';
						}
					?>
					</select>
					<br>

					<label for="">author</label>
					<input type="text" class="form-control" name="author" value="{{$book->author}}">
					<br>

					<label for="">edition</label>
					<input type="text" class="form-control" name="edition" value="{{$book->edition}}">
					<br>

					<label for="">publisher</label>
					<input type="text" class="form-control" name="publisher" value="{{$book->publisher}}">
					<br>


					<label for="sel1">Status:</label>
					<select class="form-control" name="status">
						@if($book->status == 'on')
							<option value="off">off</option>
							<option selected="" value="on">on</option>
						@else
							<option selected="" value="off">off</option>
							<option value="on">on</option>
						@endif
					</select>
					<br>

					<label for="sel1">Reserve:</label>
					<select class="form-control" name="reserve">
						@if($book->reserve == 'on')
							<option value="off">off</option>
							<option selected="" value="on">on</option>
						@else
							<option selected="" value="off">off</option>
							<option value="on">on</option>
						@endif
					</select>

				</div>
			
				{!! csrf_field() !!}
				<div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <div class="pull-right">
                <a href="{{Url('search')}}" class="btn btn-default">Cancel</a>
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