@extends('fogcms::layouts.fog')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3 col-sm-4">
            <div class="card">
                <div class="card-header">{{__('Settings groups')}}</div>

                <div class="card-body">
                    <ul class="nav flex-column d-none d-sm-block">
                        <li class="nav-item">
                            <a class="nav-link @if (Route::currentRouteName() == 'reglist') active @endif " href="{{ route('reglist') }}">{{__('Journals')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if (Route::currentRouteName() == 'attrlist') active @endif " href="{{ route('attrlist') }}">{{__('Attributes')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if (Route::currentRouteName() == 'lists') active @endif " href="{{ route('lists') }}">{{__('Lists')}}</a>
                        </li>
                    </ul>
                    <select class="form-control d-block d-sm-none" onchange="window.location.href=this.options[this.selectedIndex].value">
                            <option value="{{ route('reglist') }}" @if (Route::currentRouteName() == 'reglist') selected @endif>{{__('Journals')}}</option>
                            <option value="{{ route('attrlist') }}" @if (Route::currentRouteName() == 'attrlist') selected @endif>{{__('Attributes')}}</option>
                            <option value="{{ route('lists') }}" @if (Route::currentRouteName() == 'lists') selected @endif>{{__('Lists')}}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-9 col-sm-8">
            <div class="card">
                <div class="card-header">
                    {{ $title }}
                    @if(isset($route))
                        <a href="{{ route($route) . '/new' }} " class="float-right"><span class="mdi mdi-plus-box"></span>&nbsp;{{__('Add')}}</a>
                    @endif
                </div>
                <div class="card-body">
                    @foreach ($views as $view)
                        {!! $view !!}
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
