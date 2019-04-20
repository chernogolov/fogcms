@push('scripts')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
@endpush
@php $meterValues = $values->groupBy('MeteringDeviceNumber') @endphp
@php $dataset = [] @endphp
@php $colors = ['ХВС' => '#00aeff', 'ГВС' => '#ff0000', 'Отопление' => '#efae00', 'Электроэнергия Дн.' => '#00ef5a', 'Электроэнергия Ночн.' => '#e100ef'] @endphp
@php $services = [] @endphp
@php $monts = [] @endphp
@php $firstdate = \Carbon\Carbon::now()->format('d m Y'); @endphp

@php $vd = $values->groupBy('ValueDate'); @endphp

@if(count($values)>0)
    @php $firstdate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', current($vd->keys()->all()))->format('d m Y'); @endphp
@endif

@foreach($vd->keys()->all() as $mont)
    @php $monts[] = __(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $mont)->format('F')); @endphp
@endforeach
<a name="accepted"></a>
<div class="col-12">
    <div class="container-input mb-4">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="blue float-left">{{__('Accepted meter values')}} </h1>
                <small class="text-muted float-right">{{__('From')}} {{$firstdate}}</small>
            </div>
        </div>
        @if($devices->total()>0)
        <form method="post" class="form-horizontal" id="refreshForm" enctype="multipart/form-data" xmlns="http://www.w3.org/1999/html">
            {{ csrf_field() }}
            <div class="row mt-4">
                @foreach($devices as $device)
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="mb-3">
                                    <strong>{{$device->ServiceName}}&nbsp;<span class="text-muted">({{$device->MeteringDeviceModel}})</span></strong>
                                </h5>
                                <div class="row mb-3 text-success">
                                    <div class="col-3">
                                        {{__('Period')}}
                                    </div>
                                    <div class="col-3">
                                        {{__('Calculation method')}}
                                    </div>
                                    <div class="col-3">
                                        {{__('Scale')}}
                                    </div>
                                    <div class="col-3">
                                        {{$device->UnitName}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(isset($meterValues[$device->MeteringDeviceNumber]))
                            @php $v = $meterValues[$device->MeteringDeviceNumber]->sortByDesc('ValueDate') @endphp
                            @php $i = 0 @endphp
                            @foreach($v as $value)
                                <div class="row mb-3 ">
                                    <div class="col-3">
                                       {{__(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value->ValueDate)->format('F'))}}
                                    </div>
                                    <div class="col-3">
                                        {{$value->CalculationMethodDescription}}
                                    </div>
                                    <div class="col-3">
                                        {{$value->ScaleName}}
                                    </div>
                                    <div class="col-3">
                                        {{$value->Value}}
                                    </div>
                                </div>
                                @php $i++ @endphp
                            @endforeach
                        @endif
                        <hr>
                        <br>
                        <br>
                    </div>
                @endforeach
            </div>
        </form>
        <div class="row">
            <div class="col-12">
                <canvas id="myChart" class="w-100 mt-5" style="position:relative;"></canvas>
            </div>
            <div class="col-12 pt-4 text-center">
                <small class="text-muted">{{__('Press color block for on/off graph line')}}</small>
            </div>
        </div>
        @else
           <div class="row mt-4 mb-4">
               <div class="col-12">
                   <span class="mdi mdi-clock-fast mdi-36px d-block pt-3 float-left" style="height: 36px;"></span><span class="float-left d-block pt-3 pl-3">{{__('Data coming soon')}}</span>
               </div>
           </div>
        @endif
    </div>
</div>


@foreach($values as $vv)
    @if($vv->ServiceName == 'Электроэнергия')
        @php
            $name = $vv->ServiceName . ' ' . $vv->MeteringDeviceNumber . ' ' . $vv->ScaleName;
            $services[$vv->ServiceName . ' ' . $vv->MeteringDeviceNumber . ' ' . $vv->ScaleName] = $vv->ServiceName . ' ' . $vv->ScaleName;
            $axis_index = 1;
        @endphp
    @else
        @php
            $name = $vv->ServiceName . ' ' . $vv->MeteringDeviceNumber;
            $services[$vv->ServiceName . ' ' . $vv->MeteringDeviceNumber] = $vv->ServiceName;
            $axis_index = 1;
        @endphp
    @endif

    @if(isset($colors[$services[$name]]))
        @php $color = $colors[$services[$name]]; @endphp
    @else
        @php $color = '#AAAAAA'; @endphp
    @endif

    @if(!isset($dataset[$name]))
        @php
            $dataset[$name] = [
                'label' => $name,
                'borderColor' => $color,
                'backgroundColor' => $color,
                'fill' => false,
                'yAxisID' => 'y-axis-'.$axis_index
            ];
       @endphp
   @endif
   @php $dataset[$name]['data'][] = [
        'x' => __(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $vv->ValueDate)->format('F')),
        'y' => $vv->Value];
    @endphp
@endforeach

@php $dataset = collect($dataset)->values(); @endphp
@push('scripts')
<script>
    var monts = {!! json_encode($monts) !!};
    var lineChartData = {
        labels: monts,
        datasets: {!! json_encode($dataset) !!}
    };

    var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart.Line(ctx, {
        type: 'lineChartData',
        data: lineChartData,
        options: {
            responsive: true,
            hoverMode: 'index',
            stacked: false,
            title: {
                display: true,
                text: 'График показаний с {{$firstdate}}'
            },
            scales: {
                yAxes: [{
                    type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                    display: true,
                    position: 'left',
                    id: 'y-axis-1',
                }],
            }
        }
    });
</script>
@endpush

