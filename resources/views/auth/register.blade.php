@extends("template")
@section("content")
<div class="container">
    <div id="signupbox" style="margin-top:50px" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">Register</div>
                <div style="float:right; font-size: 85%; position: relative; top:-18px"><a id="signinlink" href="{{Url('auth/login')}}" >Sign In</a></div>
            </div>
            <div class="panel-body" >
                <form id="signupform" class="form-horizontal" role="form" method="post">

                    <div class="form-group">
                        <label for="firstname" class="col-md-4 control-label">Name</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="name" placeholder="Username">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="firstname" class="col-md-4 control-label">Username</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="username" placeholder="Username">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="col-md-4 control-label">Email</label>
                        <div class="col-md-8">
                            <input type="email" class="form-control" name="email" placeholder="Email Address">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="col-md-4 control-label">Password</label>
                        <div class="col-md-8">
                            <input type="password" class="form-control" name="password" placeholder="Password">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="col-md-4 control-label">Confirm Password</label>
                        <div class="col-md-8">
                            <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
                        </div>
                    </div>

                    <div class="form-group">
                        <!-- Button -->
                        <div class="col-md-offset-8 col-md-4">
                            <button id="btn-signup" type="submit" class="btn btn-info btn-block"><i class="icon-hand-right"></i> &nbsp Submit</button>
                            {!! csrf_field() !!}
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection