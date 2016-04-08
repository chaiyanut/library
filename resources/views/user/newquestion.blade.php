@extends("template")
@section("content")
<br>
<div class="panel panel-default">
    <div class="panel-heading">
        Webboard
    </div>
    <div class="panel-body">
        <form action="newquestion" method="post" name="frmMain" id="frmMain">
        	<div class="form-group">
                @if (Session::has('message') && Session::has('alert'))

                <div class="alert alert-{{Session::get('alert')}}" role="alert">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {{Session::get('message')}}
                </div>
                @endif
            </div>
        	<div class="form-group">
                <label class="col-md-1">Question</label>
                <input name="question" type="text" size="50">
            </div>

            <div class="form-group">
                <label class="col-md-1">Detail</label>
                <textarea name="detail" cols="100" rows="5" id="details"></textarea>
            </div>

            <div class="form-group">
                <label class="col-md-1">Name</label>
                <input name="name" type="text" id="name" value="" size="50">
            </div>

            <div class="form-group col-md-offset-1">
            	{!! csrf_field() !!}
                <input type="submit" value="Submit" class="btn btn-primary">
            	<a href="{{url('webboard')}}" class="btn btn-default">Cancel</a>
            </div>
  
        </form>
    </div>
    <div class="panel-footer"></div>
</div>
@endsection