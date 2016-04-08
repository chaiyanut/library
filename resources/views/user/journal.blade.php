@extends("template")
@section("content")

<div class="panel panel-default">
	  <div class="panel-heading">

			<center><h3>JOURNAL AND MAGAZINE</h3></center>
	  </div>
	  <div class="panel-body">

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