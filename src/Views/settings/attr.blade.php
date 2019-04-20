<form method="post" class="form-horizontal" id="editForm" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="form-group row">
        <label for="inputName" class="col-sm-4 text-left">{{__('Title')}}</label>
        <div class="col-sm-8">
            <input type="text" name="attr[title]" class="form-control" id="inputName" placeholder="заголовок" value="@isset($attr->title){{ $attr->title }}@endisset" form="editForm" autocomplete="off">
        </div>
    </div>
    <div class="form-group row">
        <label for="inputName" class="col-sm-4 text-left">{{__('Slug')}}</label>
        <div class="col-sm-8">
            <input type="text" name="attr[name]" class="form-control" id="inputName" placeholder="имя" value="@isset($attr->name){{ $attr->name }}@endisset" form="editForm" autocomplete="off">
        </div>
    </div>
    <div class="form-group row">
        <label for="inputParent" class="col-sm-4 text-left">{{__('Type')}}</label>
        <div class="col-sm-8">
            @if(!isset($attr->type))
                <select name="attr[type]" class="form-control" form="editForm">
                    <option value="chars">{{__('String')}}</option>
                    <option value="select">{{__('List')}}</option>
                    <option value="date">{{__('Date')}}</option>
                    <option value="digit">{{__('Digit')}}</option>
                    <option value="image">{{__('Image')}}</option>
                    <option value="file">{{__('File')}}</option>
                    <option value="text">{{__('Text')}}</option>
                    <option value="register">{{__('Register')}}</option>
                    <option value="link">{{__('Link')}}</option>
                    <option value="arr">{{__('Array')}}</option>
                </select>
            @else
               <span class="text-default">{{ $attr->type }}</span>
            @endif
        </div>
    </div>
    <div class="form-group row">
        <label for="inputName" class="col-sm-4 text-left">{{__('Modificator')}}</label>
        <div class="col-sm-8">
            <input type="text" name="attr[modificator]" class="form-control" id="inputName" placeholder="{{__('Modificator')}}" value="@isset($attr->modificator){{ $attr->modificator }}@endisset" form="editForm" autocomplete="off">
        </div>
    </div>
    <div class="form-group row">
        <label for="inputName" class="col-sm-4 text-left">{{__('Metadata')}}</label>
        <div class="col-sm-8">
            <input type="text" name="attr[meta]" class="form-control" id="inputName" placeholder="{{__('Metadata')}}" value="@isset($attr->meta){{ $attr->meta }}@endisset" form="editForm" autocomplete="off">
        </div>
    </div>
    <hr>
    <div class="form-group row">
        <div class="col-sm-12 text-right">
            <span class="text-muted">
                {{__('Go to list')}}&nbsp;&nbsp;<input type="checkbox" name="to_list" value="1" checked>&nbsp;&nbsp;&nbsp;
            </span>
            <button type="submit" class="btn btn-default">{{__('Save')}}</button>
        </div>
    </div>
</form>