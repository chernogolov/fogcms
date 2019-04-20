<form method="post" class="form-horizontal" id="searchForm" enctype="multipart/form-data">
    {{ csrf_field() }}
    <br>
    <div class="form-group">
        <div class="col-sm-12">
            <input
                autocomplete="off"
                type="text"
                name="text"
                data-id="{{$id}}"
                class="form-control search-link"
                data-min-length='1'
                multiple='multiple'
                id='flexdatalist-{{$id}}'
                form="searchForm"
                list="data"
                required="required"
                >
        </div>
    </div>
</form>