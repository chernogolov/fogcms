<hr>
<div class="row">
    <div class="col-sm-6">

    </div>
    <div class="col-sm-6 text-right">
        Текущий статус:
        <btn class="btn btn-sm disabled @if($status == 1) btn-danger @elseif ($status == 2) btn-warning @elseif ($status == 3) btn-success @endif">
            @if($status == 1) ОТКРЫТАЯ @elseif ($status == 2) В РАБОТЕ @elseif ($status == 3) ВЫПОЛНЕНО @endif
        </btn>
    </div>

</div>