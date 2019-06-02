@php $monts = ['','Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'] @endphp

<div class="col-12">
    <div class="container-input mb-4">
       <h1 class="blue mb-5">{{__('Bills')}}</h1>
            <div class="row">
                <div class="col-12">
                    <form method="post" class="form-horizontal" id="invoiceForm" xmlns="http://www.w3.org/1999/html">
                        {{ csrf_field() }}
                        <div class="form-row">
                            <div class="col-12">
                            <small class="text-muted">Квитанция может формироваться в течение 1-2 минут.<br>Обновите страницу чтобы скачать сформированную квитанцию</small>
                            <hr>
                            </div>
                            <div class="col">
                              <select class="form-control" form="invoiceForm" name="month">
                                <option value="1" @if(date('m')-1==1) selected @endif>Январь</option>
                                <option value="2" @if(date('m')-1==2) selected @endif>Февраль</option>
                                <option value="3" @if(date('m')-1==3) selected @endif>Март</option>
                                <option value="4" @if(date('m')-1==4) selected @endif>Апрель</option>
                                <option value="5" @if(date('m')-1==5) selected @endif>Май</option>
                                <option value="6" @if(date('m')-1==6) selected @endif>Июнь</option>
                                <option value="7" @if(date('m')-1==7) selected @endif>Июль</option>
                                <option value="8" @if(date('m')-1==8) selected @endif>Август</option>
                                <option value="9" @if(date('m')-1==9) selected @endif>Сентябрь</option>
                                <option value="10" @if(date('m')-1==10) selected @endif>Октябрь</option>
                                <option value="11" @if(date('m')-1==11) selected @endif>Ноябрь</option>
                                <option value="12" @if(date('m')-1==0) selected @endif>Декабрь</option>
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
                          <th scope="col">Дата файла</th>
                          <th scope="col" class="text-right">Файл&nbsp;<span class="mdi mdi-file-pdf mdi-18px text-success"></span></th>
                        </tr>
                      </thead>
                      <tbody>
                      @for($i = 1;$i<=12;++$i)
                          @if(Storage::exists('/public/invoice/' . $i . '/' . $account_number . '.pdf'))
                          <tr>
                            <td>{{$monts[$i]}}</td>
                            <td>{{$account_number}}</td>
                            <td>{{date('Y-m-d', Storage::lastModified('/public/invoice/' . $i . '/' . $account_number . '.pdf'))}}</td>
                            <td class="text-right"><a target="_blank" href="/storage/invoice/{{$i}}/{{$account_number}}.pdf">Скачать&nbsp;<span class="mdi mdi-download mdi-18px text-success "></span></a></td>
                          </tr>
                          @endif
                      @endfor
                      </tbody>
                    </table>
                </div>
            </div>
    </div>
</div>

