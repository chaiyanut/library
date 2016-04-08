@extends("template")
@section("content")
<div class="panel panel-default">
    <div class="panel-heading">
        <a href="{{Url('technology')}}"><center><h3>TECHNOLOGY</h3></center></a>
    </div>
    <div class="panel-body">
        <div class="col-md-3" style="margin-right:-120px; padding-left:24px;">
            <div class="form-group">

                <label style="padding-top:6px;">เลือกประเภท</label> &nbsp; <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
            </div>
        </div>

        <div class="col-md-9">
  
                    <a href="{{Url('technology/1')}}" type="button" class="btn btn-primary">Computer System</a>
                    <a href="{{Url('technology/2')}}" type="button" class="btn btn-primary">General</a>
                    <a href="{{Url('technology/3')}}" type="button" class="btn btn-primary">Information</a>
                    <a href="{{Url('technology/4')}}" type="button" class="btn btn-primary">Network And Internet</a>
                    <a href="{{Url('technology/5')}}" type="button" class="btn btn-primary">Robotic</a>
             
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
                        <td>{{$item->code}}</td>
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
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
    </div>
</div>
<nav>
    <center>
    <div class="pagination"> {!!$books->render()!!} </div>
    </center>
</nav>
</div>
</div>
@endsection

