@extends('fogcms::layouts.fog')

@section('content')
<div class="container">
    <div class="row">
    @if(Config::get('fogcms.lk_for_noreg'))
        <div class="col col-md-3">
            @foreach($nodes as $node)
            <div class="card">
                <div class="card-header">
                    {{$node->name}}
                    <a class="float-right" data-toggle="collapse" data-parent="#regs" href="#collapse-{{$node->name}}">
                        <span class="mdi mdi-chevron-down-box-outline"></span>
                    </a>
                </div>
                <div id="collapse-{{$node->name}}" class="panel-collapse collapse in lk-menu">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <ul class="nav nav-pills nav-stacked">
                                    <li @if(Route::currentRouteName() == 'lk_new') class="active" @endif>
                                        <a href="{{ route('lk_new', [$node->id])}}"><span class="mdi mdi-plus-box"></span>&nbsp;Создать</a>
                                    </li>
                                    @if (Auth::check())
                                        <li @if(Route::currentRouteName() == 'lk_list') class="active" @endif>
                                            <a href="{{ route('lk_list', [$node->id])}}"><span class="mdi mdi-format-list-bulleted"></span>&nbsp;Список</a>
                                        </li>
                                    @endif
                                    <li @if(Route::currentRouteName() == 'lk_check') class="active" @endif>
                                        <a href="{{ route('lk_find', [$node->id])}}"><span class="mdi mdi-magnify"></span>&nbsp;Поиск по номеру</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
        <div class="col-md">
            <div class="card">
                <div class="card-header">
                    {{ $title }}
                    @if(isset($route))
                        <a href="{{ route($route) . '/new' }} " class="float-right"><span class="mdi mdi-plus-box"></span>&nbsp;Добавить</a>
                    @endif
                </div>
                <div class="card-body" style="padding-bottom: 0">
                    @foreach ($views as $view)
                        {!! $view !!}
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
