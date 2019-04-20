@extends('fogcms::layouts.lk')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col content pt-3">
                <div class="row">
                    <div class="col-lg-3">
                        @foreach ($sidebar as $bar)
                            {!! $bar !!}
                        @endforeach
                    </div>
                    <div class="col content mt-4">
                        <div class="row">
                        @isset($views)
                            @foreach ($views as $view)
                                {!! $view !!}
                            @endforeach
                        @endisset
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
