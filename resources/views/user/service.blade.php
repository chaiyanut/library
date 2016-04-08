@extends("template")
@section("content")
<div class="panel panel-default">
    <div class="panel-body">
        <p>
        <h3>รายการจองหนังสือ</h3><br>
        </p>
        <form action="" method="POST" role="form" id="books">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th class="col-md-2">รหัส</th>
                        <th class="col-md-3">ชื่อหนังสือ</th>
                        <th class="col-md-2">ประเภท</th>
                        <th class="col-md-1">วันที่จอง</th>
                        <th class="col-md-2">หมดเขตจอง</th>
                        <th class="col-md-1"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; ?>
                    @foreach($books as $item)
                    <tr>
                        <td>{{$i+1}}</td>
                        <td>{{$item->code}}</td>
                        <td>{{$item->name}}</td>
                        <td>{{ucfirst($item->category)}}</td>
                        <td>
                        @if($reserves)
                        {{substr($reserves[$i]->created_at, 0, 10)}}
                        @endif
                        </td>
                        <td>{{date("Y-m-d", strtotime(substr($reserves[$i]->created_at, 0, 10)) + (60*60*24*3))}}</td>
                        <td><a href="reserve/remove/{{$item->id}}" class="btn" style="background-color:#eee; border-color:#999">Remove</a></td>
                    </tr>
                    <?php $i++; ?>
                    @endforeach
                </tbody>
            </table>
        </form>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-body">
        <p>
        <h3>รายการยืม/คืนหนังสือ</h3><br>
        </p>
        <form action="" method="POST" role="form" id="books">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th class="col-md-2">รหัส</th>
                        <th class="col-md-3">ชื่อหนังสือ</th>
                        <th class="col-md-2">ประเภท</th>
                        <th class="col-md-1">วันที่ยืม</th>
                        <th class="col-md-1">กำหนดคืน</th>
                        <th class="col-md-1">วันที่คืน</th>
                        <th class="col-md-1">ค่าปรับ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; $day = 60*60*24?>
                    @foreach($borrows as $item)
                    <tr>
                        <td>{{$i+1}}</td>
                        <td>{{$item->code}}</td>
                        <td>{{$item->name}}</td>
                        <td>{{ucfirst($item->category)}}</td>
                        <td>{{substr($borrows_date[$i]->created_at, 0, 10)}}</td>

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
                        <td>
                        @if($borrows_date[$i]->returned)
                            {{substr($borrows_date[$i]->updated_at, 0, 10)}}
                        @else
                            -
                        @endif
                        </td>
                        <td>
                        @if($borrows_date[$i]->fined)
                         {{$borrows_date[$i]->fined}} บาท
                        @else
                         -
                        @endif
                        </td>

                    </tr>
                    <?php $i++; ?>
                    @endforeach
                </tbody>
            </table>
        </form>
    </div>
</div>
@endsection