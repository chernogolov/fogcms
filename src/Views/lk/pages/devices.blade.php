<div class="col-12">
    <div class="container-input mb-4">
        <h1 class="blue">{{__('Devices')}}</h1>
        <form method="post" class="form-horizontal" id="devicesForm" xmlns="http://www.w3.org/1999/html">
            {{ csrf_field() }}
            @if($devices->total()>0)
                <div class="table-responsive mt-5">
                    <table class="table">
                        <tr>
                            <th>
                                {{__('Communal service')}}
                            </th>
                            <th>
                                {{__('Model')}}
                            </th>
                            <th>
                                {{__('Number')}}
                            </th>
                            <th>
                                {{__('Updated at')}}
                            </th>
                            <th>
                                {{__('Verification Date')}}
                            </th>
                        </tr>
                        @foreach($devices as $device)
                            <tr>
                                <td>
                                    {{$device->ServiceName}}
                                </td>
                                <td>
                                    {{$device->MeteringDeviceModel}}
                                </td>
                                <td>
                                    {{$device->MeteringDeviceNumber}}
                                </td>
                                <td>
                                    {{$device->updated_at}}
                                </td>
                                <td>
                                    @if($device->VerificationDate > 0){{date('d-m-Y', $device->VerificationDate) }}@endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    <small class="float-right text-muted ">{{__('Data updated at every 2 week')}}</small>
                </div>
            @else
                <div class="row mt-4 mb-4">
                    <div class="col-12">
                        <span class="mdi mdi-clock-fast mdi-36px d-block pt-3 float-left" style="height: 36px;"></span><span class="float-left d-block pt-3 pl-3">{{__('Data coming soon')}}</span>
                    </div>
                </div>
            @endif
            <div class="row mt-4">
                <div class="col-12">
                    <button class="btn btn-green float-lg-right" form="devicesForm" type="submit" value="true" name="update-devices">
                    <span class="mdi mdi-check-all"></span>&nbsp;
                    {{__('Update devices')}}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

