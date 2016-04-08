@extends("template")
@section("content")
<br>
<div class="panel panel-default">
<div class="panel-heading">
    <h3>Webboard</h3>
</div>
<div class="panel-body">

    <div class="table-responsive">
    	<table class="table table-boder table-striped">
    		<thead>
    			<tr>
					<th class="col-md-1">No.</th>
					<th class="col-md-5">Question</th>
					<th class="col-md-1">Name</th>
					<th class="col-md-3">CreateDate</th>
					<th class="col-md-1">View</th>
					<th class="col-md-1">Reply</th>
					<th></th>
				</tr>
    		</thead>
    		<tbody>
    			<?php $count = 1; ?>
    			@foreach($questions as $i)
	    			<tr><center>
	    				<td>{{$count}}</div></td>
					    <td><a href="viewquestion/{{$i->id}}">{{$i->question}}</a></a></td>
					    <td>{{$i->name}}</td>
					    <td>{{$i->created_at}}</div></td>
					    <td>{{$i->view}}</td>
					    <td>{{$i->reply}}</td>
					    </center>
					     @if(Auth::check() && Auth::user()->role=='admin')
                        <td><a href="webboard/delete/{{$i->id}}" class="btn btn-danger" onclick="return confirm('Are you sure to delete')"><i class="fa fa-trash-o"></i></a></td>
                        @endif

	    			</tr>
	    			<?php $count++; ?>
    			@endforeach
    		</tbody>
    	</table>
    </div>
    <div class="btn-group pull-right" role="group" >
		<a href="{{url('home')}}" class="btn btn-default">Back</a>
		<a href="{{url('newquestion')}}" class="btn btn-primary">New Topic</a>

	</div>
</div>
</div>
@endsection