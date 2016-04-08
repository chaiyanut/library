@extends("template")
@section("content")
<div class="panel panel-default">
    <div class="panel-body">
        <center><img src="http://www.library.coj.go.th/images/search1.gif" class="img img-responsive"></center>
        <br>
        <div class="col-md-12">
            <br>
            <form action="search" method="POST" role="form">
                <div class="col-md-2" style="margin-right:-80px; padding-left:24px;">
                    <div class="form-group">
                        <label style="padding-top:6px;">ค้นหาโดย</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select name="cat" class="form-control" required="required">
                            @if(isset($cat))
                                <option value="name" 
                                @if($cat == 'name') selected=""
                                @endif
                                >Name</option>
                                <option value="code"
                                @if($cat == 'code') selected=""
                                @endif
                                >Code</option>
                                <option value="isbn"
                                @if($cat == 'isbn') selected=""
                                @endif
                                >ISBN</option>
                            @else
                            <option value="name">Name</option>
                            <option value="code">Code</option>
                            <option value="isbn">ISBN</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-8">
                    
                        <div class="col-md-4">
                            <input type="text" name="keyword" class="form-control" 
                            @if(isset($keyword))
                            value="{{$keyword}}"
                            @endif
                            >
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="{{url('search')}}" class="btn btn-primary">Cancel</a> 
                        </div>
                        {!! csrf_field() !!}
                </div>
            </form>
        </div>
        <form action="" method="POST" role="form" id="books">
            <table class="table table-hover">
                <thead>
                    <tr>
                        @if(isset($delete) && $delete==1)
                        <th></th>
                        @endif
                        <th>id</th>
                        <th>code</th>
                        <th class="col-md-4">name</th>
                        <th>category</th>
                        <th>year</th>
                        <th>ISBN</th>
                        <th>status</th>
                        <th>reserve</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($books as $item)
                    <tr>
                        @if(isset($delete) && $delete==1)
                        <td><input type="checkbox" name="checkbox[]" value="{{$item->id}}"></td>
                        @endif
                        <td>{{$item->id}}</td>
                        <td><a href="{{Url('book-detail')}}/{{$item->id}}">{{$item->code}}</a></td>
                        <td>{{$item->name}}</td>
                        <td>{{ucfirst($item->category)}}</td>
                        <td>{{$item->year}}</td>
                        <td>{{$item->isbn}}</td>
                        <td><center>
                        @if($item->status=='on')
                        <i style="color:#01C301;" class="fa fa-check"></i>
                        @else
                        <i style="color:#F00;" class="fa fa-ban"></i>
                        @endif
                        </center></td>
                         <td><center>
                        @if($item->reserve=='off')
                        <i style="color:#01C301;" class="fa fa-check"></i>
                        
                        @else
                        <i style="color:#F00;" class="fa fa-ban"></i>
                        @endif
                        </td></center>

                        @if(Auth::check() && Auth::user()->role=='admin')
                            <td><a href="edit/{{$item->id}}" class="btn" style="background-color:#eee; border-color:#999">Edit</a></td> 
                        @elseif($item->reserve=='off')
                            <td><a href="reserve/{{$item->id}}" class="btn btn-success">จอง</a></td>
                        @elseif($item->reserve=='on')
                            <td><a class="btn btn-default" disabled style="color:#888;">จอง</a></td>
                        @endif

                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div align="right">
                @if(Auth::check() && Auth::user()->role=='admin')
                    @if(isset($delete) && $delete==1)
                        {!! csrf_field() !!}
                        <button type="button" data-toggle="modal" data-target="#confirmModal" class="btn btn-danger">Delete</button>
                        <a href="{{url('search')}}" class="btn btn-default">Cancel</a>
                    @else
                        <a href="{{url('insert')}}" class="btn btn-primary">Insert</a>
                        <a href="{{url('delete')}}" class="btn btn-danger">Delete</a>
                    @endif
                @endif
                
            </div>
        </form>
    </div>
</div>
<nav>
    <center>
    <div class="pagination"> {!!$books->render()!!} </div>
    </center>
</nav>
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
        $('#books').trigger('submit');
    });
});
</script>
@endsection