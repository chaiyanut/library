<nav class="navbar navbar-default navbar-fixed-top">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="col-md-12">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class= "navbar-brand" href="#">MENU</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav">
                <li><a href="{{Url('home')}}">Home</a></li>
                <li><a href="{{Url('webboard')}}">Webboard</a></li>

                @if(Auth::check() && Auth::user()->role!='admin')
                <li><a href="{{url('service')}}">Service</a></li>
                @endif
                <li><a href="{{url('search')}}">Search</a></li>
                @if(Auth::check() && Auth::user()->role=='admin')
                    <li><a href="{{url('borrow')}}">Borrow</a></li>
                @endif

            </ul>
                
            <ul class="nav navbar-nav navbar-right">

                @if(Auth::check())
                    <li>
                        <a href="{{url('viewprofile')}}">{{Auth::user()->username}}</a>
                    </li>
                    <li>
                        <a href="{{url('admin/logout')}}"><span class="glyphicon glyphicon-off" aria-hidden="true"></span> Logout</a>
                    </li>

                @else
                    <li>
                        <a href="{{url('auth/login')}}">Login</a>
                    </li>
                @endif

               
            </ul>
        </div><!-- /.navbar-collapse -->
    </div>
</nav>