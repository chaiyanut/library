@extends("template")
@section("content")
<br>
<div class="panel panel-default">
<div class="panel-heading">
    ข่าว
</div>
<div class="panel-body">
    <center>
        <h2>{{$news->title}}</h2>
        <hr>
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <img src="{{Url('assets/uploads')}}/{{$news->img}}" class="img-responsive col-md-13" alt="Image">
            </div>
        </div>
        <br>
        <br>
        
        <p>{{$news->detail}}</p>
    </center>
    <br>
    <form action="{{Url('news')}}" method="post" id="news">
        <div class="btn-group pull-right" role="group" >
    		<a href="{{url('home')}}" class="btn btn-default">&nbspBack&nbsp</a>
            <input type="hidden" name="id" value="{{$news->id}}">
    		@if(Auth::check() && Auth::user()->role=='admin')
    			<a href="{{url('update-news')}}/{{$news->id}}" class="btn btn-primary">&nbsp&nbspEdit&nbsp&nbsp</a>
    			<button type="button" data-toggle="modal" data-target="#confirmModal" class="btn btn-danger">Delete</button>
            @endif
    	</div>
        {!! csrf_field() !!}
	</form>
</div>
</div>

<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="submit" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Delete book(s)</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to delete selected book ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="confirm">Confirm</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<script src="//code.jquery.com/jquery.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#confirm').on('click', function(e){
        $('#news').trigger('submit');
    });
});
</script>
@endsection