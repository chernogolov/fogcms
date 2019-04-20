@extends('fogcms::layouts.fog')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3 col-sm-4">
            <div class="card">
                <div class="card-header">{{__('Options groups')}}</div>
                <div class="card-body">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link @if (Route::currentRouteName() == 'account') active @endif" href="{{ route('options') . '/account' }}">{{__('Account')}}</a>
                        </li>
                        {{--<li class="nav-item">--}}
                            {{--<a class="nav-link @if (Route::currentRouteName() == 'privacy-policy') active @endif" href="{{ route('options') . '/privacy-policy' }}">{{__('Privacy-policy')}}</a>--}}
                        {{--</li>--}}
                        {{--@if (Gate::allows('view-regs'))--}}
                            {{--<li class="nav-item">--}}
                                {{--<a class="nav-link @if (Route::getCurrentRoute()->group == 'view') active @endif " href="{{ route('options') . '/view' }}">{{__('View')}}</a>--}}
                            {{--</li>--}}
                        {{--@endif--}}
<!--                        <li @if (Route::currentRouteName() == 'notify_regs') class="active" @endif >-->
<!--                            <a href="{{ route('options') . '/notify' }}">Оповещения</a>-->
<!--                        </li>-->
<!--                        <li @if (Route::getCurrentRoute()->group == 'house') class="active" @endif >-->
<!--                            <a href="{{ route('options') . '/house' }}">Мой дом</a>-->
<!--                        </li>-->
                    </ul>
<!--                    <select class="form-control visible-xs" onchange="window.location.href=this.options[this.selectedIndex].value">-->
<!--                        <option value="" disabled selected>Выберете раздел</option>-->
<!--                        <option value="{{ route('options') . '/account' }}">Аккаунт</option>-->
{{--<!--                        @if (Gate::allows('view-regs'))<option value="{{ route('options') . '/view' }}">Внешний вид панели</option>@endif-->--}}
<!--                        <option value="{{ route('options') . '/user' }}">Оповещения</option>-->
<!--                        <option value="{{ route('options') . '/house' }}">Мой дом</option>-->
<!--                    </select>-->
                </div>
            </div>
        </div>
        <div class="col-md-9 col-sm-8">
            <div class="card">
                <div class="card-header">@if(isset($title)) {{ $title }} @else {{__('Options')}} @endif</div>
                <div class="card-body">
                    @if(isset($views))
                        @foreach ($views as $vd)
                            {!! $vd !!}
                        @endforeach
                    @endif

                    @if(isset($options) && is_array($options))
                    <form method="post" class="form-horizontal" id="optionsForm">
                        {{ csrf_field() }}
                        @foreach($options as $key => $option)
                            <div class="row">
                                <div class="col-sm-4">
                                    {{ $option['title'] }}
                                </div>
                                <div class="col-sm-8">
                                    @if($option['type'] === 'select')
                                        <select name="options[{{ $key }}]" class="form-control" form="optionsForm">
                                            @foreach($option['values'] as $value)
                                                <option value="{{ $value }}" @if($value === $option['value']) selected @endif>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @elseif($option['type'] === 'char')
                                        <input type="{{ $option['subtype'] }}" name="options[{{ $key }}]" class="form-control" form="optionsForm" @isset($option['value']) value="{{ $option['value'] }}" @endisset>
                                    @endif
                                </div>
                            </div>
                        <br>
                        @endforeach
                        <br>
                        <button type="submit" class="btn btn-default float-right">Сохранить</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
