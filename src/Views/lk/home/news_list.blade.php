@if(!empty($news))
<div class="col-12">
    <div class="container-input left-blue-line pt-3 pl-3 pr-3 pb-0 mb-4 home-news">
        <div class="row">
            @foreach($news as $item)
                <div class="col-12 col-sm-6 mb-3">
                    <div class="media">
                     <a href="{{route('news-item', ['id' => $item->id])}}">
                          <img class="mr-3 img-fluid" src="@if(isset($item->photo) && strlen($item->photo)>0)/imagecache/small/{{$item->photo}}@else /public/img/default-news-list.jpg @endif" alt="login">
                     </a>
                      <div class="media-body">
                        <h5 class="mt-2 mb-2 "><strong><a href="{{route('news-item', ['id' => $item->id])}}">{{$item->title}}</a></strong></h5>
                        <div class="mt-2 fs-13">
                            {!!str_limit(strip_tags($item->entry), 160)!!}
                        </div>
                      </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
