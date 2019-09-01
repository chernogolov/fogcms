<form method="post" class="form-horizontal ajax-form" id="editForm" data-destination="records">
    {{ csrf_field() }}

    <div class="form-group row">
        <label for="inputName" class="col-sm-4 float-left">Заголовок</label>
        <div class="col-sm-8">
            <input type="test" name="record[title]" class="form-control" id="inputName" placeholder="имя" form="editForm" autocomplete="off">
        </div>
    </div>
    <div class="form-group row">
        <label for="inputName" class="col-sm-4 float-left">Журналы</label>
        <div class="col-sm-8">
            <select id="multiple-selected" multiple="multiple" style="visibility: hidden" name="regs[]">
                @foreach($nodes as $item)
                    @if(isset($node->id))
                        <option value="{{ $item->id }}" @if($node->id == $item->id) selected @endif @if($item->is_summary === "1") disabled @endif>
                    @else
                        <option value="{{ $item->id }}" @if($item->is_summary === "1") disabled @endif>
                    @endif
                        @for ($i = 0; $i < $item->depth; $i++)&nbsp;&nbsp;@endfor{{ $item->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
