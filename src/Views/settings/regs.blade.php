<form id="regsForm" method="post">
    {{ csrf_field() }}
    <div class="sortable ui-sortable-handle">
    @for ($n = 0;$n< count($nodes); $n++)
        <div class="col-sm-12">
            @for ($i = 0; $i < $nodes[$n]->depth; $i++)
                &nbsp;
            @endfor
            @if($nodes[$n]->id !=1)
                <input type="checkbox" name="delete[]" value="{{ $nodes[$n]->id }}">
            @endif

            <a href="{{ route('reglist') . '/' . $nodes[$n]->id}}"> {{ $nodes[$n]->name }}</a>
            <div class="float-right">
                @if(isset($nodes[$n-1]))
                    @if($nodes[$n-1]->depth == $nodes[$n]->depth)
                        <a href="{{ route('reglist') . '?moveleft=' . $nodes[$n]->id}}"><span class="mdi mdi-chevron-up"></span></a>
                    @else
                        <span class="mdi mdi-chevron-up opacity2" title=""></span>
                    @endif
                @else
                    <span class="mdi mdi-chevron-up opacity2" title=""></span>
                @endif
                @if(isset($nodes[$n+1]))
                    @if($nodes[$n+1]->depth == $nodes[$n]->depth)
                        <a href="{{ route('reglist') . '?moveright=' . $nodes[$n]->id}}"><span class="mdi mdi-chevron-down"></span></a>
                    @else
                        <span class="mdi mdi-chevron-down opacity2" title=""></span>
                    @endif
                @else
                    <span class="mdi mdi-chevron-down opacity2" title=""></span>
                @endif
            </div>
        </div>
    @endfor
    </div>
    <div class="clearfix"></div>
    <hr>
    <div class="clearfix"></div>
    <button type="submit" name="delete_sel" class="btn btn-default" onclick="return confirm('{{__('Confirm delete')}}?');">{{__('Delete selected')}}</button>
</form>