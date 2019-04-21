<div class="col-12">
    <div class="container-input">
            <div class="row">
                <div class="col-12">
                     @if(isset($item['photo']) && strlen($item['photo'])>0)
                         <div class="popup-gallery">
                            <a href="/imagecache/original/{{$item['photo']}}">
                                <img class="float-left mr-3 mb-3" src="/imagecache/medium/{{$item['photo']}}" alt="login">
                            </a>
                         </div>
                     @endif
                     <h1 class="blue mb-3">{{$item['title']}}</h1>
                     @isset($item['Date'])<small>{{$item['Date']}}</small>@endisset
                     <div class="mt-3">
                         @isset($item['entry']){{$item['entry']}}@endisset
                     </div>
                         <div class="clearfix"></div>
                     <div class="mt-3">
                        {!!$item['description']!!}
                     </div>
                     <hr>
                     <a class="btn btn btn-outline-dark mt-2 float-left" href="{{route('lk')}}">{{__('Home')}}</a>
                     <a class="btn btn btn-outline-dark mt-2 float-right" href="{{route('news')}}">{{__('News list')}}</a>
                </div>
            </div>
    </div>
</div>

<script>
    $(function(){
        $('.popup-gallery').magnificPopup({
            delegate: 'a',
            type: 'image',
            tLoading: 'Загрузка #%curr%...',
            mainClass: 'mfp-img-mobile',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0,1] // Will preload 0 - before current, and 1 after the current image
            },
            image: {
                tError: '<a href="%url%">Ошибка загрузки #%curr%.</a>',
                titleSrc: function(item) {
                    return item.el.attr('title');
                }
            }
        });
    });
</script>