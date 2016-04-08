@extends("template")
@section("content")
<br><br>
<div class="">
    <div class="col-md-6 col-md-offset-3 ">
        <div class="panel ">
            <div class="panel-body">
                <legend>รายละเอียด</legend>
                <div class="panel panel-default">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td><b>รหัส</b></td>
                                    <td>{{$book->code}}</td>
                                </tr>
                                <tr>
                                    <td><b>ชื่อหนังสือ</b></td>
                                    <td>{{$book->name}}</td>
                                </tr>
                                <tr>
                                    <td><b>ประเภท</b></td>
                                    <td>{{$book->category}}</td>
                                </tr>
                                <tr>
                                    <td><b>ผู้แต่ง</b></td>
                                    <td>{{$book->author}}</td>
                                </tr>
                                <tr>
                                    <td><b>พิมพ์ครั้งที่</b></td>
                                    <td>{{$book->edition}}</td>
                                </tr>
                                <tr>
                                    <td><b>สำนักพิมพ์</b></td>
                                    <td>{{$book->publisher}}</td>
                                </tr>
                                <tr>
                                    <td><b>ปี</b></td>
                                    <td>{{$book->year}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-4 pull-right">
                	<a href="{{Url('home')}}" class="btn btn-default btn-block">Back</a>
                
                </div>
                 @if(Auth::check() && Auth::user()->role=='admin')
                <div class="col-md-4">
                    <a type="button" class="btn btn-danger" id="Delete" href="{{Url('delete-suggest')}}/{{$book->id}}">Delete</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection