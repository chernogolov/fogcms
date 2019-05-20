<div class="col-12">
    <div class="container-input">
       <h1 class="blue mb-5">{{__('News')}}</h1>
            <div class="row">
                @foreach($news as $item)
                    <div class="col-12">
                        <div class="media">
                         <a href="{{route('news-item', ['id' => $item->id])}}">
                              <img class="mr-3 img-fluid" src="@if(isset($item->photo) && strlen($item->photo)>0)/imagecache/small/{{$item->photo}}@else /img/default-news-list.jpg @endif" alt="login">
                         </a>
                          <div class="media-body">
                            <h5 class="mt-0 mb-2 float-lg-left"><strong><a href="{{route('news-item', ['id' => $item->id])}}">{{$item->title}}</a></strong></h5>
                            @if($item->Date)<small class="float-lg-right">{{date('d-m-Y', $item->Date)}}</small>@endif<br>
                            <div class="mt-2 d-none d-sm-block" style="height: 31px;overflow: hidden">
                                {!!str_limit(strip_tags($item->entry), 160)!!}
                            </div>
                            <a class="btn btn-sm btn-outline-dark mt-2 d-none d-lg-inline-block" href="{{route('news-item', ['id' => $item->id])}}">{{__('More')}}</a>
                            <span class="text-success btn btn-sm btn-light float-lg-right mt-2 d-none d-lg-block">{{$item->multiaddress}}</span>
                          </div>
                        </div>
                        <a class="btn btn-sm btn-outline-dark mt-2 d-inline-block d-lg-none" href="{{route('news-item', ['id' => $item->id])}}">{{__('More')}}</a>
                        <span class="text-success btn btn-sm btn-light float-lg-right mt-2 d-block d-lg-none">{{$item->multiaddress}}</span>
                        <hr>
                    </div>
                @endforeach
            </div>
    </div>
</div>

