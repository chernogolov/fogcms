<div class="row">
    <div class="col-sm-4">
        <label class="control-label">{{ $attr->title }}</label>
    </div>
    <div class="col-sm-8">
        <div class="row">
            @if(isset($attr->values))
                <div class="popup-gallery">
                    @foreach($attr->values as $v)
                        <div class="col-sm-4 col-xs-6 item-image">
                            <a href="/imagecache/original/{{ $v->value }}" title="">
                                <img src="/imagecache/small/{{ $v->value }}" class="img-responsive" alt="" title="">
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
<br>
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