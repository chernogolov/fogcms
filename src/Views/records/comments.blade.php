<div class="row">
    <div class="col-sm-12">
        <h4>
            {{__('Comments')}}
        </h4>
    </div>
</div>
@if(count($data)>0)
<form method="post" class="form-horizontal" id="deleteForm" enctype="multipart/form-data" action="{{ route('deletecomments', ['rid' => $rid])}}">
    {{ csrf_field() }}
    <div class="row" style="background: #fafafa;padding-top: 15px;">
        @foreach($data as $item)
            <div class="col-sm-4">
                <strong>{{ $item->name }}<br>
                {{ $item->email }}</strong><br>
                <small class="text-muted">{{ $item->added_on }}</small>
                @if($item->user_id == $user_id)
                    <br><br><input type="checkbox" class="for_check" name="delete[{{ $item->id }}]" form="deleteForm">&nbsp;<span class="text-muted">{{__('Delete')}}</span>
                @endif
            </div>
            <div class="col-sm-8">
                <div class="row">
                    @if(isset($item->images))
                        <div class="popup-gallery2">
                            @foreach($item->images as $img)
                                <div class="col-sm-3 col-xs-6">
                                    <div>
                                        <a href="/imagecache/original/{{ $img->image }}" target="_blank" title="">
                                            <img src="/imagecache/small/{{ $img->image }}" class="img-responsive" style="margin-bottom: 15px;">
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="clearfix"></div>
                <div style="background: #fff;padding: 10px;margin: 10px 0;border-radius: 3px;border: #f6f6f6 solid 1px;">
                    {{ $item->text }}
                </div>
            </div>
         <div class="col-sm-12"><hr class="white-hr"></div>
        @endforeach
        <div class="clearfix"></div>
        <div class="col-sm-12">
            <button form="deleteForm" data-destination="comments"  class="btn btn-default float-right submit" onclick="return confirm('Действительно удалить?');">{{__('Delete selection')}}</button>
        </div>
        <div class="clearfix"></div>
        <br>
    </div>
</form>
@endif
<form method="post" class="form-horizontal" id="addForm" enctype="multipart/form-data" action="{{ route('editcomments', ['rid' => $rid])}}">
    {{ csrf_field() }}
    <div class="row" style="background: #fafafa;padding-top: 15px;">
        <div class="col-sm-4">
            <strong>
                {{__('Your comment')}}
            </strong>
        </div>
        <div class="col-sm-8">
            <div class="image-preview">
            </div>
            <input type="file" form="addForm" multiple="multiple" name="comment[image][]" class="form-control" id="inputImage" placeholder="{{__('Select image')}}" form="editForm"
                   accept="image/*">
            <br>
            <textarea name="comment[text]" class="form-control" form="addForm" rows="5">@if(isset($comments->text)){{ $comments->text }}@endif</textarea>
            <label id="comment_text" class="hidden text-danger"></label>
            @if ($errors->has('comment.text'))
                <span class="text-danger">
                    <strong>{{ $errors->first('comment.text') }}</strong>
                </span>
            @endif
        </div>
        <div class="col-sm-12" style="padding-top: 15px;">
            <div class="form-group row">
                <div class="col-sm-12 text-right">
                    <div class="d-block d-sm-none">
                        <br>
                        <button form="addForm" data-destination="comments" data-btn="comment-xs" id="comment-xs-btn" class="btn btn-primary form-control submit">{{__('Send')}}</button>
                    </div>
                    <button form="addForm" data-destination="comments" data-btn="comment" id="comment-btn" class="btn btn-default hidden-xs submit">{{__('Send')}}</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function()
    {
        $('#inputImage').bind('change', function(){
            var data = new FormData();
            var error = '';
            jQuery.each($('#inputImage')[0].files, function(i, file) {
                if(file.name.length < 1) {
                    error = error + ' Файл имеет неправильный размер! ';
                }
                if(file.size > 1000000) {
                    error = error + ' File ' + file.name + ' is to big.';
                }
                if(file.type != 'image/png' && file.type != 'image/jpg' && !file.type != 'image/gif' && file.type != 'image/jpeg' ) {
                    error = error + 'File  ' + file.name + '  doesnt match png, jpg or gif';
                }

                data.append('file-'+i, file);

            });

            $.ajax({
                type:'POST', // Тип запроса
                url: '/upload_image', // Скрипт обработчика
                data: data, // Данные которые мы передаем
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                cache:false, // В запросах POST отключено по умолчанию, но перестрахуемся
                contentType: false, // Тип кодирования данных мы задали в форме, это отключим
                processData: false, // Отключаем, так как передаем файл
                beforeSend: function() {
                    $('#inputImage').prop('disabled',true);
                    $('.image-preview').append('<img src="/vendor/fogcms/img/load_min.gif" id="comment_loader">');
                },
                success:function(data){
                    $('#comment_loader').remove();
                    var files = $.parseJSON(data);
                    var i = 0;
                    files.forEach(function(f) {
                        i++;
                        $('.image-preview').append('<div class="ci_wrp" id="img_'+i+'"><img src="/imagecache/small/'+f+'" alt="" title=""><div class="remove" id="'+i+'" data-delete="'+f+'">удалить</div><input type="hidden" name="comment[loaded][]" value="'+f+'"></div>');
                    });
                    $('#inputImage').prop('value', '');
                    $('#inputImage').prop('disabled',false);
                },
                error:function(data){
                    console.log(data);
                }
            });
        });

        $('.popup-gallery2').magnificPopup({
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

        $('body').on('click', '.remove', function(){
            $(this).each(function(){
               var filename = $(this).data('delete');
               var id = $(this).prop('id');
               $.ajax({
                    type:'POST', // Тип запроса
                    url: '/delete_image', // Скрипт обработчика
                    data: {'filename':filename}, // Данные которые мы передаем
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    cache:false, // В запросах POST отключено по умолчанию, но перестрахуемся
                    success:function(data){
                        $('#img_'+id).remove();
                    },
                    error:function(data){
                        console.log(data);
                    }
                });

            });
        });
    });
</script>
