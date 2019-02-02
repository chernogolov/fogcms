<div class="row">
    <div class="col-sm-4">
        <label class="control-label">{{ $attr->title }}</label>
    </div>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="attr[{{ $attr->name }}]">
    </div>
</div>
<br>