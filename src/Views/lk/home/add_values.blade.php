<div class="col-12">
    <div class="container-input p-3 mb-4">
       <h3 class="blue">{{__('Add devices values')}}</h3>
       <hr>
       <form method="post" class="form-horizontal mt-2" id="sendValuesForm" enctype="multipart/form-data"
         xmlns="http://www.w3.org/1999/html">
       {{ csrf_field() }}
        @if($devices->total()>0)
            @foreach($devices as $device)
                <div class="form-group row">
                    <label for="inputName" class="col-lg-7 col-form-label">{{$device->ServiceName}} <br class="d-block d-lg-none"><span class="text-muted">({{$device->UnitName}})</span></label>
                    <div class="col-lg-5">
                        @php $scale = json_decode($device->Scales) @endphp
                        @php $scale = $scale->MeteringDeviceScale @endphp
                        @if(is_array($scale))
                            <div class="row">
                                @foreach($scale as $s)
                                    <div class="col-6">
                                        <div class="input-group">
                                          <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><span @if(str_slug($s->Name) == 'dn') class="mdi mdi-white-balance-sunny" title="{{__('Day')}}" @else class="mdi mdi-weather-night" title="{{__('Night')}}" @endif></span></span>
                                          </div>
                                            <input type="number" min="0" step="1" name="values[{{$device->id}}][{{str_slug($s->Name)}}]" class="form-control" form="sendValuesForm" autocomplete="off" placeholder="0" value="{{ old('values.'.$device->id . str_slug($s->Name)) }}">
                                            @if ($errors->has('values.'.$device->id . str_slug($s->Name)))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('values.'.$device->id . str_slug($s->Name)) }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @elseif(is_object($scale))
                            <input type="number" min="0" step="0.01" name="values[{{$device->id}}][{{str_slug($scale->Name)}}]" class="form-control" form="sendValuesForm" autocomplete="off" placeholder="0,00" value="{{ old('values.'.$device->id . str_slug($scale->Name)) }}">
                            @if ($errors->has('values.'.$device->id . str_slug($scale->Name)))
                                <span class="help-block">
                                    <strong>{{ $errors->first('values.'.$device->id . str_slug($scale->Name)) }}</strong>
                                </span>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="row mt-4 mb-4">
                <div class="col-12">
                    <span class="mdi mdi-clock-fast mdi-36px d-block pt-3 float-left" style="height: 36px;"></span><span class="float-left d-block pt-3 pl-3">{{__('Data coming soon')}}</span>
                </div>
            </div>
        @endif
           <hr>
           <div class="form-group row">
               <div class="col-8">
                   <small class="text-muted float-left fs-13 d-inline-block pt-2">Показания принимаются до 24 числа текущего месяца включительно. С 25 показания будут приняты к учёту в следующем месяце.</small>
               </div>
               <div class="col-4">
                   <button type="submit" form="sendValuesForm" class="btn btn-green float-right" value="true" name="send-values">{{__('Send')}}</button>
               </div>
           </div>
       </form>
    </div>
</div>

