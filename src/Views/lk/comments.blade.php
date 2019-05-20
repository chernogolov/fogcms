@if(count($data)>0)
@php $my = false @endphp
<form method="post" class="form-horizontal" id="deleteForm" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="row pb-4" style="background: #f9f9f9" >
        <div class="col-lg-12">
            <h2 class="mb-4 mt-4">
               {{__('Ansver')}}
           </h2>
       </div>
        @foreach($data as $item)
            <div class="col-lg-5 col-12">
                <div class="row mb-2">
                    <div class="col-3">
                        <img class="rounded-circle img-fluid w-100" src="@if(isset($item->image) && strlen($item->image)>0)/imagecache/avatar/{{$item->image}}@else /img/default-user.jpg @endif" alt="login">
                    </div>
                    <div class="col-9">
                        <p class="mb-1"><small>{{ $item->name }}</small></p>
                        <p class="mb-1"><small>{{ $item->email }}</small></p>
                        <p class="text-muted"><small>{{ $item->added_on }}</small></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 mb-4">
                <div class="row">
                    @if(isset($item->images))
                        <div class="popup-gallery-c">
                            @foreach($item->images as $img)
                                <div class="col-lg-3 col-6">
                                    <a href="/imagecache/original/{{ $img->image }}" target="_blank" title="">
                                        <img src="/imagecache/small/{{ $img->image }}" class="img-responsive" alt="" title="">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="clearfix"></div>
                <div style="background: #fff;padding: 10px;margin: 10px 0;border-radius: 3px;border: #f6f6f6 solid 1px;">
                    {{ $item->text }}
                </div>
                <div class="float-right">
                    @if($item->user_id == $user_id)
                        @php $my = true @endphp
                        <input type="checkbox" class="for_check" id="del_{{ $item->id }}" name="delete[{{ $item->id }}]" form="deleteForm">&nbsp;<label for="del_{{ $item->id }}" class="text-muted">удалить</label>
                    @endif
                </div>
            </div>
            <div class="col-12">
                <div class="w-100 pt-3 pb-3" style="border-top:#eee solid 1px;"></div>
            </div>
        @endforeach
        <div class="clearfix"></div>
        @if($my)
        <div class="col-lg-12">
            <button form="deleteForm" class="btn btn-default float-right" onclick="return confirm('{{__('Confirm delete')}}');">{{__('Delete selected')}}</button>
        </div>
        <div class="clearfix"></div>
        @endif
    </div>
</form>
<form method="post" class="form-horizontal" id="addForm" enctype="multipart/form-data" action="{{ route('add-feedback', ['id' => $rid])}}">
    {{ csrf_field() }}
    <div class="row mt-2 pb-4" style="background: #f2f2f2">
        <br>
        <div class="col-lg-12">
           <h2 class="mb-4 mt-4">
               {{__('Your feedback')}}
           </h2>
        </div>
        <div class="col-lg-12">
            <textarea name="comment[text]" class="form-control" form="addForm" rows="5">@if(isset($comments->text)){{ $comments->text }}@endif</textarea>
            <label id="comment_text" class="hidden text-danger"></label>
            @if ($errors->has('comment.text'))
                <span class="text-danger">
                    <strong>{{ $errors->first('comment.text') }}</strong>
                </span>
            @endif
        </div>
        <div class="col-sm-12">
            <button form="addForm" data-destination="comments" data-btn="comment" id="comment-btn" class="btn btn-default submit float-right">{{__('Send')}}</button>
        </div>
    </div>
</form>
@endif
<script>
    $(function(){
        $('.popup-gallery-c').magnificPopup({
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