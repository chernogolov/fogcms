@php $months = [] @endphp

<div class="col-12">
    <div class="container-input mb-4">
       <h1 class="blue mb-5">{{__('Bills')}}</h1>
            <div class="row">
                <div class="col-12">
                    <form method="post" class="form-horizontal" id="invoiceForm" xmlns="http://www.w3.org/1999/html">
                        {{ csrf_field() }}
                        <div class="form-row">
                            @php $date = \Carbon\Carbon::now(); @endphp
                            <div class="col">
                              <select class="form-control" form="invoiceForm" name="month">
                              @for($m = 1;$m < 6;$m++)
                                <option value="{{$date->subMonth(1)->format('n')}}"  @if(\Carbon\Carbon::now()->subMonth(1)->format('n') == $date->format('n')) selected @endif >{{$date->format('Y')}} {{__($date->format('F'))}}</option>
                                @php $months[$date->format('n')] = $date->format('Y') . ' ' . __($date->format('F')) @endphp
                              @endfor
                              </select>
                            </div>
                            <div class="col">
                                <button type="submit" form="invoiceForm" class="btn btn-green float-right" value="true" name="generate-invoice">{{__('Generate')}}</button>
                            </div>
                          </div>
                    </form>
                </div>
                <div class="col-12">
                    <table class="table mt-5">
                      <thead>
                        <tr>
                          <th scope="col">Месяц</th>
                          <th scope="col">ЛС</th>
                          <th scope="col" class="text-right">Файл&nbsp;<span class="mdi mdi-file-pdf mdi-18px text-success"></span></th>
                        </tr>
                      </thead>
                      <tbody>
                          @foreach($months as $key => $month)
                              @if(Storage::exists('/public/invoice/' . $key . '/' . $account['account_number'] . '.pdf') || (is_array($tmp_kvit) && in_array($key, $tmp_kvit)))
                                  <tr>
                                    <td>{{$month}}</td>
                                    <td>{{$account['account_number']}}</td>
                                      @if(Storage::exists('/public/invoice/' . $key . '/' . $account['account_number'] . '.pdf'))
                                          <td class="text-right"><a download href="/storage/invoice/{{$key}}/{{$account['account_number']}}.pdf">Скачать&nbsp;<span class="mdi mdi-download mdi-18px text-success"></span></a></td>
                                      @else
                                          <td class="text-right"><a href="/finance">Обновить&nbsp;<span class="mdi mdi-refresh mdi-18px text-success "></span></a></td>
                                      @endif
                                  </tr>
                              @endif
                          @endforeach
                      </tbody>
                    </table>
                </div>
                <div class="col-12">
                    <hr>
                    <small class="text-muted">Квитанция может формироваться в течение 1-2 минут.<br>Обновите страницу чтобы скачать сформированную квитанцию</small>
                </div>
            </div>
    </div>
</div>

