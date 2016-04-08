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
                                    <td><b>Name</b></td>
                                    <td>{{Auth::user()->name}}</td>
                                </tr>
                                <tr>
                                    <td><b>Username</b></td>
                                    <td>{{Auth::user()->username}}</td>
                                </tr>
                                <tr>
                                    <td><b>Email</b></td>
                                    <td>{{Auth::user()->email}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-8 pull-right">
                	<a href="{{Url('editprofile')}}" class="btn" style="background-color:#eee; border-color:#999">Edit</a>
                	<a href="{{Url('dashboard')}}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
	  

@endsection