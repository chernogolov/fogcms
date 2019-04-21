<div class="col-12">
    <div class="container-input">
        <h1 class="blue mb-5">{{__('Quest - ansver')}}</h1>
        <div class="row">
            @foreach($data as $key => $item)
                <a name="{{str_slug($key)}}"></a>
                <div class="col-12 mb-4">
                    <h2>
                        {{$key}}
                    </h2>
                    <div class="accordion" id="accordion{{str_slug($key)}}">
                        @foreach($item as $it)
                              <div class="card">
                                <div class="card-header" id="heading{{$it->id}}">
                                  <h5 class="mb-0">
                                    <button class="btn btn-link text-left" type="button" data-toggle="collapse" data-target="#collapse{{$it->id}}" aria-expanded="true" aria-controls="collapse{{$it->id}}">
                                      {!!$it->question!!}
                                    </button>
                                  </h5>
                                </div>
                                <div id="collapse{{$it->id}}" class="collapse " aria-labelledby="heading{{$it->id}}" data-parent="#accordion{{str_slug($key)}}">
                                  <div class="card-body">
                                     {!!$it->ansver!!}
                                  </div>
                                </div>
                              </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
