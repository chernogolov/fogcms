<div class="card-header">
    # {{ $data->id }}
    @isset($back)
        <a class="ajax float-right" href="{{$back}}"><span class="mdi mdi-backspace-outline"></span>&nbsp;{{__('Back')}}</a>
    @endisset
</div>
<div class="card-body">
    <div class="row">
        <div class="col-sm-6 col-xs-12 text-left">
            <small class="text-muted">
                {{__('Created at')}}: {{ $data->created_at }} <br> {{__('Updated at')}}: {{ $data->updated_at }}
            </small>
        </div>
        <div class="col-sm-6  col-xs-12 right left-xs">
        <br class="d-block d-sm-none">
        <span id="status_{{$data->id}}">
            @if($data->status == 1) {{__('New')}} @elseif ($data->status == 2) {{__('In work')}} @elseif ($data->status == 3) {{__('Completed')}} @elseif ($data->status == 4) {{__('Closed by author')}} @endif
        </span>
            <br>
            <div class="btn-group" style="margin-top: 5px;">
                <div class="dropdown">
                    <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{__('Change status')}}&nbsp;<span class="caret"></span>
                    </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <a data-sid="1" data-id="{{$data->id}}" class="dropdown-item change_status" @if($data->status != 1) href="{{ route('change_status', ['rid' => $data->id]) }}" @endif>{{__('New')}}</a>
                        <a data-sid="2" data-id="{{$data->id}}" class="dropdown-item change_status" @if($data->status != 2) href="{{ route('change_status', ['rid' => $data->id]) }}" @endif>{{__('In work')}}</a>
                        <a data-sid="3" data-id="{{$data->id}}" class="dropdown-item change_status" @if($data->status != 3) href="{{ route('change_status', ['rid' => $data->id]) }}" @endif>{{__('Success')}}</a>
                      </div>
                </div>
            </div>

        </div>
    </div>
    <hr>

