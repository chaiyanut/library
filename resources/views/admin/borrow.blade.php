@extends("template")
@section("content")
<div class="panel panel-default">
    <div class="panel-body">
        <h2><center>ยืมหนังสือ</center></h2><hr>
        <div class="col-md-12 ">
            <form action="{{Url('borrow/find')}}" method="POST" role="form">
                <div class="col-md-2" style="margin-right:-60px; padding-left:24px;">
                    <div class="form-group">
                        <label style="padding-top:6px;">รหัสนักศึกษา</label>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="col-md-4">
                        <input type="text" name="sid" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">ค้นหา</button>
                    </div>
                    {!! csrf_field() !!}
                </div>
            </form>
        </div>

         <div class="col-md-12 ">
            <form action="{{Url('code/find')}}" method="POST" role="form">
                <div class="col-md-2" style="margin-right:-60px; padding-left:24px;">
                    <div class="form-group">
                        <label style="padding-top:6px;">รหัสหนังสือ</label>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="col-md-4">
                        <input type="text" name="keyword" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">ค้นหา</button>
                    </div>
                    {!! csrf_field() !!}
                </div>
            </form>
        </div>

        <form action="" method="POST" role="form" id="borrow">
            <div class="row">
                <div class="col-md-5 col-md-offset-1" style="padding-top:20px;padding-left:53px;" >
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form class="form-horizontal" role="form">
                                <fieldset>
                                    <!-- Form Name -->
                                    <legend>&nbsp;ข้อมูลนักศึกษา</legend>
                                    <!-- Text input-->
                                    <div class="form-group">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="textinput" style="text-align:right;">รหัสนักศึกษา</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <label>
                                                :
                                                @if(isset($user))
                                                {{$user->username}}
                                                @endif
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="textinput" style="text-align:right;">ชื่อ-นามสกุล</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <label>
                                                :
                                                @if(isset($user))
                                                {{$user->name}}
                                                @endif
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="textinput" style="text-align:right;">ชั้นปี</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <label>
                                                :
                                                @if(isset($user))
                                                {{ substr((date("Y")+543), 2, 2) - substr($user->username, 0, 2) }}
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        @if(isset($search))
        <hr>
        <h3>ค้นหาหนังสือ</h3><br>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ลำดับ</th>
                                    <th>รหัส</th>
                                    <th>ชื่อหนังสือ</th>
                                    <th>วันที่จอง</th>
                                    <th>หมดเขตจอง</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($search))
                                <?php $i = 0; ?>
                                @foreach($search as $item)
                                <tr>
                                    <td>{{$i+1}}</td>
                                    <td>{{$item->code}}</td>
                                    <td>{{$item->name}}</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><a href="{{Url('borrow/approve')}}/{{$item->id}}" class="btn btn-default" onclick="return confirm('ยืนยันการยืม ?')" style="background-color:#fefefe;"><i class="fa fa-check-square-o"></i> ยืม</a></td>
                                </tr>
                                <?php $i++;?>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <hr>
        <h3>รายการจองหนังสือ</h3><br>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ลำดับ</th>
                                    <th>รหัส</th>
                                    <th>ชื่อหนังสือ</th>
                                    <th>วันที่จอง</th>
                                    <th>หมดเขตจอง</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($reserves))
                                <?php $i = 0; ?>
                                @foreach($reserves as $item)
                                <tr>
                                    <td>{{$i+1}}</td>
                                    <td>{{$item->code}}</td>
                                    <td>{{$item->name}}</td>
                                    <td>
                                    @if($reserves_date)
                                    {{substr($reserves_date[$i]->created_at, 0, 10)}}
                                    @endif
                                    </td>
                                    <td>{{date("Y-m-d", strtotime(substr($reserves_date[$i]->created_at, 0, 10)) + (60*60*24*3))}}</td>
                                    <td><a href="{{Url('borrow/approve')}}/{{$item->id}}" class="btn btn-default" onclick="return confirm('ยืนยันการยืม ?')" style="background-color:#fefefe;"><i class="fa fa-check-square-o"></i> ยืม</a></td>
                                </tr>
                                <?php $i++;?>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <h3>รายการยืม/คืนหนังสือ</h3><br>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ลำดับ</th>
                                    <th>รหัส</th>
                                    <th>ชื่อหนังสือ</th>
                                    <th>วันที่ยืม</th>
                                    <th>กำหนดคืน</th>
                                    <th>วันที่คืน</th>
                                    <th><center>ค่าปรับ (บาท)</center></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($borrows))
                                <?php $i = 0; ?>
                                @foreach($borrows as $item)
                                <tr>
                                    <td>{{$i+1}}</td>
                                    <td>{{$item->code}}</td>
                                    <td>{{$item->name}}</td>
                                    <td>{{substr($borrows_date[$i]->created_at, 0, 10)}}</td>
                                    <td>{{date("Y-m-d", strtotime(substr($borrows_date[$i]->created_at, 0, 10)) + (60*60*24*5))}}</td>
                                    <td>
                                    @if($borrows_date[$i]->returned)
                                        {{substr($borrows_date[$i]->updated_at, 0, 10)}}
                                    @else
                                        -
                                    @endif
                                    </td>
                                    <td><center>
                                    @if($borrows_date[$i]->fined)
                                     {{$borrows_date[$i]->fined}}
                                    @else
                                     -
                                    @endif
                                    </center>
                                    </td>
                                    <td>
                                    @if($borrows_date[$i]->returned)
                                        <a  onclick="return confirm('คืหนังสือเรียบร้อย')" class="btn btn-default" style="color:#888;">คืนแล้ว</a>
                                    @else
                                        <a href="{{Url('return/approve')}}/{{$item->id}}" onclick="return confirm('ยืนยันการคืน ?')" class="btn btn-success">คืน</a>
                                    @endif
                                    </td>
                                </tr>
                                <?php $i++;?>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<nav>
    <center>
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