<div class="col-12">
    <div class="container-input">
       <h4 class="blue">{{__('Edit objects')}}</h4>
       <form method="post" class="form-horizontal mt-5" id="editForm" enctype="multipart/form-data"
          xmlns="http://www.w3.org/1999/html">
        {{ csrf_field() }}

        <hr>
        <div class="form-group row">
            <div class="col-12">
                <button type="submit" class="btn btn-green float-right">{{__('Save')}}</button>
            </div>
        </div>
    </form>
    </div>
</div>

