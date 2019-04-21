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
                     <h1 class="blue mb-3">{{$item['fio']}}</h1>
                     @isset($item['post'])<small>{{$item['post']}}</small>@endisset
                     @if(isset($item['phone']) && strlen($item['phone'])>0)
                         <div class="mt-3 mb-2 lead">
                             <span class="mdi mdi-phone"></span>&nbsp;<a href="tel:{{$item['phone']}}">{{$item['phone']}}</a>
                         </div>
                     @endif
                     @if(isset($item['email']) && strlen($item['email'])>0)
                         <div class="mt-3 mb-2 lead">
                             <span class="mdi mdi-email"></span>&nbsp;<a href="mailto:{{$item['email']}}">{{$item['email']}}</a>
                         </div>
                     @endif
                         <div class="clearfix"></div>
                     <div class="mt-3 mb-4">
                        {!!$item['contact']!!}
                     </div>
                     <a class="btn btn btn-outline-dark mt-2 float-left" href="{{route('lk')}}">{{__('Home')}}</a>
                     <a class="btn btn btn-outline-dark mt-2 float-sm-right" href="{{route('contacts')}}">{{__('Contact book')}}</a>
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