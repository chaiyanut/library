@extends("template")
@section("content")
<br>
<div class="col-md-offset-1 col-md-10 panel panel-default">
    <div class="panel-body">
        <div class="col-md-offset-3 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{$question->question}}
                </div>
                <div class="panel-body">
                    {{$question->details}}
                </div>
                <div class="panel-footer">
                    @if(isset($reply))
                        @foreach($reply as $i)

                            <p><a href=""><?php echo DB::table('users')->where('id', $i->user_id)->first()->username ?></a>
                            : {{$i->details}}</p>

                        @endforeach
                    @endif
                    <form action="{{Url('viewquestion')}}", method="post">
                         <textarea name="reply" id="input" class="form-control" rows="2" required="required"></textarea>
                        <div class="btn-group pull-right" role="group" style="padding-top:10px;">
                            <button type="submit" class="btn btn-success btn-sm">&nbspReply&nbsp</button>
                            <a href="{{url('webboard')}}" class="btn btn-default btn-sm">&nbspBack&nbsp</a>
                            <input type="hidden" name="id" value="{{$question->id}}">
                            {!! csrf_field() !!}
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection