<div class="col-12">
    <div class="container-input mb-4">
        <h1 class="blue mb-5">{{__('F.A.Q.')}}</h1>
        <div class="row">
            @foreach($data as $key => $item)
                <a name="{{str_slug($key)}}"></a>
                <div class="col-lg-4 col-6 mb-4">
                    <h5>
                        <a href="{{route('qa')}}#{{str_slug($key)}}">{{$key}}</a>
                    </h5>
                </div>
            @endforeach
        </div>
    </div>
</div>
