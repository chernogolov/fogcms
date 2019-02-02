<hr>
<div class="form-group row">
    <div class="col-sm-12 text-right">
        @isset($back)
            <a class="ajax btn btn-default hidden-xs float-left" href="{{$back}}"><span class="mdi mdi-remove"></span>&nbsp;Закрыть</a>
        @endisset
            <span class="text-muted">
                После сохранения перейти в список&nbsp;&nbsp;<input form="editForm"  type="checkbox" name="to_list" value="1" @if(isset($to_list)) {{$to_list}} @endif>&nbsp;&nbsp;&nbsp;
            </span>
        <div class="visible-xs">
            <br>
            <button type="submit" data-destination="records" data-btn="save-xs" id="save-xs-btn" form="editForm" class="btn btn-primary form-control submit">Сохранить</button>
        </div>
        <button type="submit" data-destination="records" data-btn="save" id="save-btn" form="editForm" class="btn btn-default hidden-xs submit">Сохранить</button>
    </div>
</div>
</form>
</div>