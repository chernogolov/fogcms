<div class="d-none d-lg-block">
    <div class="slidebar-left d-flex flex-column justify-content-between mt-4 ">
        <a class="d-flex flex-column justify-content-between" href="{{route('lk')}}">
            @if(isset($current_account['address.photo']))
                <img src="/imagecache/house/{{$current_account['address.photo']}}" class="img-fluid">
            @else
                <img src="/public/img/ekb.jpg" class="img-fluid">
            @endif
        </a>
        <div class="object-selector">
            @if(isset($accounts) && !empty($accounts))
                @if($accounts->count()>1)
                    <div class="dropdown change-account  w-100">
                        <a title="{{__('Change account')}}" class="dropdown-toggle btn btn-dark-blue w-100" style="cursor: pointer" id="AccountsDropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{$current_account['address.fulladdress']}} {{__('room')}} {{$current_account['room']}}
                        </a>
                        <div class="dropdown-menu dropdown-menu-dark-blue w-100" aria-labelledby="AccountsDropdownMenuButton">
                            @foreach($accounts as $acc)
                                @if($acc->id != $current_account['id'])
                                    <a class="dropdown-item dropdown-dark-blue text-center" href="{{route('lk-account-change', ['id' => $acc->id])}}">
                                        {{$acc->address}} {{__('room')}} {{$acc->room}}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @else
                    @if(isset($current_account['account_number']))
                        <span class="btn btn-dark-blue" title="{{$current_account['account_number']}}">{{$current_account['address.fulladdress']}} {{__('room')}} {{$current_account['room']}}</span>
                    @else
                        <a class="btn-dark-blue" href="{{route('lk-accounts')}}">{{__('Add yor account!')}}<span class="mdi mdi-home-plus mdi-18px" style="margin-left: 10px;"></span></a>
                    @endif
                @endif
            @else
                {{__('Add yor account!')}}&nbsp;&nbsp;<span class="mdi mdi-home-plus mdi-18px"></span>
            @endif
        </div>
        <div class="btn-wrp mt-4">
            <a href="{{route('new-ticket')}}" class="btn btn-green btn-small w-100">{{__('Ticket')}}<span>&nbsp;&nbsp;+</span></a>
        </div>
        <ul class="list-group mt-4">
            <li class="list-group-item">
                <a href="{{route('support')}}" >{{__('Support center')}}</a>
            </li>
            <li class="list-group-item">
                <a href="{{route('utilities')}}">{{__('Utilites')}}</a>
            </li>
            <li class="list-group-item active">
                <a href="{{route('finance')}}">{{__('Finance')}}</a>
            </li>
        </ul>
        <div class="logout mt-5">
            <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();" href="{{ route('logout') }}" class="d-flex justify-content-between"><span>Выход из системы</span><img src="/vendor/chernogolov/fogcms/public/img/exit_btn.png" alt="exit"></a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</div>
<div class="d-block d-lg-none">
    <div class="mobile-slidebar d-flex flex-column justify-content-between mt-4">
        <div class="row">
            <div class="col-4">
                <a class="d-flex flex-column justify-content-between" href="{{route('lk')}}">
                    @if(isset($current_account['address.photo']))
                        <img src="/imagecache/house/{{$current_account['address.photo']}}" class="img-fluid">
                    @else
                        <img src="/public/img/ekb.jpg" class="img-fluid">
                    @endif
                </a>
            </div>
            <div class="col-8 pt-1 pl-0">
                @if(isset($accounts) && !empty($accounts))
                    @if($accounts->count()>1)
                        <div class="dropdown change-account mt-2 w-100">
                            <a title="{{__('Change account')}}" class="dropdown-toggle text-left btn w-100" style="cursor: pointer" id="AccountsDropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{$current_account['address.fulladdress']}} {{__('room')}} {{$current_account['room']}}<br>
                                {{$current_account['account_number']}}
                            </a>
                            <div class="dropdown-menu w-100" aria-labelledby="AccountsDropdownMenuButton">
                                @foreach($accounts as $acc)
                                    @if($acc->id != $current_account['id'])
                                        <a class="dropdown-item text-left" href="{{route('lk-account-change', ['id' => $acc->id])}}">
                                            {{$acc->address}} {{__('room')}} {{$acc->room}}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @else
                        @if(isset($current_account['account_number']))
                            <span class="btn mt-2 w-100 text-left" title="{{$current_account['account_number']}}">{{$current_account['address.fulladdress']}} {{__('room')}} {{$current_account['room']}}<br>{{$current_account['account_number']}}</span>
                        @else
                            <a class="mt-3 w-100 text-center d-inline-block" href="{{route('lk-accounts')}}">{{__('Add yor account!')}}<span class="mdi mdi-home-plus mdi-18px" style="margin-left: 10px;"></span></a>
                        @endif
                    @endif
                @else
                    <a class="mt-3 w-100 text-center d-inline-block" href="{{route('lk-accounts')}}">{{__('Add yor account!')}}<span class="mdi mdi-home-plus mdi-18px" style="margin-left: 10px;"></span></a>
                @endif
            </div>
        </div>
    </div>
</div>
