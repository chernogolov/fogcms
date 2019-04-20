<div class="col-12">
    <div class="container-input mb-4">
        <form method="post" class="form-horizontal" id="deleteForm" enctype="multipart/form-data" xmlns="http://www.w3.org/1999/html">
           {{ csrf_field() }}
           <div class="row">
               <div class="col-lg-6">
                   <h1 class="blue mb-5 mb-lg-0">{{__('Sendet values')}}</h1>
               </div>
               <div class="col-lg-6 text-lg-right">
                   Данные показания будут приняты к учету до конца текущего месяца.
               </div>
           </div>
           <div class="row mt-5">
                @php $noitems = true @endphp
                @foreach($devices as $device)
                    @if(isset($values[$device->id]) && $values[$device->id]->total()>0)
                        @php $noitems = false @endphp
                        <div class="col-12">
                            <div class="row">
                            <div class="col-12">
                                <h5 class="mb-1">
                                    {{$device->ServiceName}}
                                </h5>
                                <span class="text-muted">
                                    {{$device->MeteringDeviceNumber}}
                                </span>
                            </div>
                            </div>
                            <hr>
                                @foreach($values[$device->id] as $value)
                                <div class="row mb-3">
                                    <div class="col-2">
                                        <input type="checkbox" name="delete[{{$value->id}}]">
                                    </div>
                                    @php $scale = json_decode($device->Scales) @endphp
                                    @php $scale = $scale->MeteringDeviceScale @endphp
                                    @if(is_array($scale))
                                        <div class="col-5">
                                            {{date('d-m-Y', $value->SendDate)}}
                                        </div>
                                        <div class="col-5">
                                            <div class="row">
                                                @foreach($scale as $s)
                                                <div class="col-6">
                                                    @php $name = str_slug($s->Name) @endphp
                                                    <span @if(str_slug($name) == 'dn') class="mdi mdi-white-balance-sunny" title="{{__('Day')}}" @else class="mdi mdi-weather-night" title="{{__('Night')}}" @endif></span>
                                                    {{$value->$name / 100}} <span class="fs-13 text-muted">{{$device->UnitName}}</span>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-5">
                                            {{date('d-m-Y', $value->SendDate)}}
                                        </div>
                                        <div class="col-5">
                                            @php $name = str_slug($scale->Name) @endphp
                                            {{$value->$name / 100}} <span class="fs-13 text-muted">{{$device->UnitName}}</span>
                                        </div>
                                    @endif
                                </div>
                                @endforeach
                            <br>
                            <br>
                        </div>
                    @endif
                @endforeach
                <div class="col-12">
                    @if($noitems)
                        {{__('No meters values data')}}
                    @else
                       <hr>
                       <button type="submit" form="deleteForm" class="btn btn-default btn-sm float-right" name="delete-values" onclick="return confirm('{{__('Are you sure?!')}}')">{{__('Delete selected')}}</button>
                    @endif
                </div>
           </div>
        </form>
    </div>
</div>

