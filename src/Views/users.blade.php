@extends('fogcms::layouts.fog')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3 col-sm-4">
            <div class="card">
                <div class="card-header">{{__('User settings')}}</div>

                <div class="card-body">
                    <ul class="nav flex-column d-none d-sm-block">
                        <li class="nav-item">
                            <a class="nav-link @if (Route::currentRouteName() == 'users') active @endif" href="{{ route('users') }}">{{__('Users')}}</a>
                        </li>
                        {{--<li class="nav-item">--}}
                            {{--<a  class="nav-link @if (Route::currentRouteName() == 'users_regs') active @endif">{{__('Journals')}}</a>--}}
                        {{--</li>--}}
                    </ul>
                    <select class="form-control d-block d-sm-none" onchange="window.location.href=this.options[this.selectedIndex].value">
                        <option value="{{ route('users') }}" @if (Route::currentRouteName() == 'users') selected @endif>{{__('Users')}}</option>
{{--                        <option value="{{ route('users_regs') }}" @if (Route::currentRouteName() == 'users_regs') selected @endif>{{__('Journals')}}</option>--}}
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-9 col-sm-8">
            <div class="card">
                <div class="card-header">
                    {{ isset($title) ? $title : __('Users') }}
                    <a href="{{ route('new_user') }}" class="float-right"><span class="mdi mdi-plus-box"></span>&nbsp;{{__('Add')}}</a>
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
