@extends("template")
@section("content")
<style type="text/css">
    .lib-head {
        margin-top: -220px;
        margin-left: 40px;
        padding-bottom: 170px;
        color: white;
    }
</style>
<div style="background-color:#f6f6f6;" class="panel panel-default panel-body">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="">
                    <center><img width="100%" height="auto"src="assets/img/home-library-study-area.jpeg"></center>
                    <h1 class="lib-head"> Welcome to CoE Library</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- Left panel -->
    <div class="col-md-12">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div style="background-color:#F3F7F7; color:#000000; padding:20px;" class="col-md-12">
                        <form action="search" method="POST" role="form">
                            <div class="col-md-2" style="margin-right: -30px; padding-left: 0px;">
                                <div class="form-group">
                                    <label style="padding-top:6px;">ค้นหาโดย</label>
                                </div>
                            </div>
                            <div class="col-md-3">
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
                            <div class="col-md-7">

                                <div class="col-md-8 row">
                                    <input type="text" name="keyword" class="form-control"
                                    @if(isset($keyword))
                                    value="{{$keyword}}"
                                    @endif
                                    >
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                                {!! csrf_field() !!}
                            </div>
                        </form>
                    </div>
                    <div class="col-md-12 text-center">
                        <div class="page-header text-center">
                            <h1><small>ประเภทหนังสือ</small></h1>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="thumbnail">
                                    <img src="http://www.khianaksorn.com/images/book.jpg"/>
                                    <p><a href="http://127.0.0.1/laravel-master/public/from">Project Report</a></p>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="thumbnail">
                                    <img src="http://www.rvpprinting.com/picbook/2511-656.jpg"/>
                                    <p><a href="{{url('journal')}}">Journal and magazine</a></p>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="thumbnail">
                                    <img src="https://pornpailin4285.files.wordpress.com/2013/06/6.jpg"/>
                                    <p><a href="http://127.0.0.1/laravel-master/public/technology">technology</a></p>
                                    <p></p>

                                </div>
                              
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Right pnl -->
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <center><h3>ข่าวประชาสัมพันธ์</h3></center>
                    <ul>
                        @foreach($news as $i)
                        <li><a href="news/{{$i->id}}">{{$i->title}}</a>
                        <?php

                        if(((time()-strtotime($i->updated_at))/( 60 * 60 * 24 )) < 7)
                            echo '<img src="http://www.coe.phuket.psu.ac.th/2013/images/new2.gif">';

                        echo '<span class="pull-right" >'. substr($i->updated_at, 0, 10) . '<span>';
                        ?>
                        </li>
                        @endforeach
                    </ul>
                    @if(Auth::check() && Auth::user()->role=='admin')
                    <a href="{{url('createnews')}}" class="btn btn-primary btn-sm pull-right">Create News</a>
                    @endif
                </div>
            </div>
            @if(Auth::check() && Auth::user()->role!='admin')
            <div class="panel panel-default">

                <div class="panel-body">
                    <p>
                    <center><h3>รายการยืมหนังสือ</h3></center>
                    <br>
                    </p>
                    <form action="" method="POST" role="form" id="books">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="col-md-3">ชื่อหนังสือ</th>
                                    <th class="col-md-1">กำหนดคืน</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; $day = 60*60*24?>
                                @foreach($borrows as $item)
                                <tr>
                                    <td>{{$item->name}}</td>
                                    <!-- return date -->
                                    @if($borrows_date[$i]->returned == 1)
                                    <td style="color:black;">

                                    @elseif( round((time()-strtotime(substr($borrows_date[$i]->created_at, 0, 10)))/$day) > 5)
                                    <td style="color:red;">

                                    @elseif( round((time()-strtotime(substr($borrows_date[$i]->created_at, 0, 10)))/$day) > 4)
                                    <td style="color:orange;">

                                    @elseif( round((time()-strtotime(substr($borrows_date[$i]->created_at, 0, 10)))/$day) > 3)
                                    <td style="color:yellow;">

                                    @elseif( round((time()-strtotime(substr($borrows_date[$i]->created_at, 0, 10)))/$day) > 2)
                                    <td style="color:green;">

                                    @else
                                    <td style="color:green;">
                                    @endif

                                    {{date("Y-m-d", strtotime(substr($borrows_date[$i]->created_at, 0, 10)) + $day*5)}}</td>
                                    
                                    <!-- end return date -->

                                </tr>
                                <?php $i++; ?>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
            @endif

        </div>
        <div>
            
        </div>
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <center>
                    <h3>แนะนำหนังสือ</h3>
                    <?php $i = 0; ?>
                    @foreach($suggests as $item)
                        <div class="col-md-3">
                            <a href="{{Url('book-detail')}}/{{$item->book_id}}">
                            <img src="{{Url('assets/uploads')}}/{{$item->img}}" class="img img-responsive img-rounded" width="60%"></a>
                            <!-- <p>{{$books[$i]->name}}</p> -->
                        </div>
                        <?php $i++; ?>
                    @endforeach
                    </center>
                    
                    
                </div>
                @if(Auth::check() && Auth::user()->role=='admin')
                    <a href="{{url('createbooks')}}" class="btn btn-primary btn-sm pull-right">Create Books</a>
                    @endif
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="row panel panel-info panel-body">
        <center>
        สำนักหอสมุด ภาควิชาวิศวกรรมศาสตร์ มหาวิทยาลัยสงขลานครินทร์ วิทยาเขตภูเก็ต
        <br>
        (Office of the CoE Library, Prince of songkla University,Phuket Campus )
        <br>
        <span class="glyphicon glyphicon-education" aria-hidden="true"></span>
        </center>
    </div>
</div>
@endsection