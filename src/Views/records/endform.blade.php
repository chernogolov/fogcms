<hr>
<div class="row mb-3">
    <div class="col-3">
        <label class="control-label">
            {{__('Destination')}}
        </label>
    </div>
    <div class="col-9 destination">
        <select multiple="multiple" name="destination[]" form="editForm">
                @foreach($nodes as $n)
                    @if($n->id == $node->id)
                        <option value="{{ $n->id }}" selected>@for ($i = 0; $i < $n->depth; $i++)&nbsp;&nbsp;@endfor{{ $n->name }}</option>
                    @else
                        @if($n->is_summary !== "1" && $n->type === $node->type)
                            <option value="{{ $n->id }}">
                                @for ($i = 0; $i < $n->depth; $i++)&nbsp;&nbsp;@endfor{{ $n->name }}
                            </option>
                        @endif
                    @endif
                @endforeach
        </select>
    </div>
</div>
<div class="form-group row">
    <div class="col-sm-12 text-right">
        @isset($back)
            <a class="ajax btn btn-default hidden-xs float-left btn-outline-dark" href="{{$back}}"><span class="mdi mdi-remove"></span>&nbsp;Закрыть</a>
        @endisset
            <span class="text-muted">
                После сохранения перейти в список&nbsp;&nbsp;<input form="editForm"  type="checkbox" name="to_list" value="1" @if(isset($to_list)) {{$to_list}} @endif>&nbsp;&nbsp;&nbsp;
            </span>
        <div class="d-block d-sm-none">
            <br>
            <button type="submit" data-destination="records" data-btn="save-xs" id="save-xs-btn" form="editForm" class="btn btn-primary form-control submit">Сохранить</button>
        </div>
        <button type="submit" data-destination="records" data-btn="save" id="save-btn" form="editForm" class="btn btn-dark hidden-xs submit">Сохранить</button>
    </div>
</div>
</form>
</div>

<script>
    $(document).ready(function() {
        $(".destination select").multipleSelect({
            filter: true
        });
    });
</script>