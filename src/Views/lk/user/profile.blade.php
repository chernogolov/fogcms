<div class="col-12">
    <div class="container-input">
       <h1 class="blue">{{__('Edit your profile')}}</h1>
       <form method="post" class="form-horizontal mt-5" id="editForm" enctype="multipart/form-data"
          xmlns="http://www.w3.org/1999/html">
        {{ csrf_field() }}
        <div class="form-group row">
            <label for="inputPhoto" class="col-sm-4 col-form-label">{{__('Your photo')}}</label>
            <div class="col-sm-8">
                <div class="row">
                    @if(isset($user->image) && strlen($user->image)>0)
                        <div class="col-6 ">
                            <img class="img-fluid user-avatar" src="@if(isset($user->image))/imagecache/original/{{$user->image}}@else /public/img/default-user.jpg @endif" alt="{{__('Avatar')}}">
                        </div>
                        {{--<div class="col-6 ">--}}
                            {{--<div class="preview-wrapper" style="position: relative;overflow:hidden;width:80px;height:80px;">--}}
                                {{--<img class="preview" src="/imagecache/original/{{$user->image}}" alt="{{__('Preview')}}">--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    @else
                        <div class="col-6 ">
                            <img class="img-fluid" src="/public/img/default-user.jpg " alt="{{__('Preview')}}">
                        </div>
                    @endif
                </div>
                <input type="file" accept="image/jpeg,image/png" name="user[image]" class="form-control mt-4" form="editForm" placeholder="{{__('Change you photo')}}">
                @if ($errors->has('user.image'))
                    <span class="help-block">
                        <strong>{{ $errors->first('user.image') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="inputName" class="col-sm-4 col-form-label">{{__('E-Mail Address') . ' (' . __('Your Login') . ')'}} @if(!isset($user->email)) <strong class="text-danger" >*</strong> @endif</label>
            <div class="col-sm-8">
                <input type="text" disabled="disabled" name="user[email]" @if(isset($user->email)) value="{{ $user->email }}" @endif class="form-control" form="editForm" autocomplete="off" required="required">
            </div>
        </div>
        <div class="form-group row">
            <label for="inputName" class="col-sm-4 col-form-label">{{__('Fio')}} <strong class="text-danger">*</strong></label>
            <div class="col-sm-8">
                <input type="text" name="user[name]" @if(isset($user->name)) value="{{ $user->name }}" @endif class="form-control" id="inputName" form="editForm" autocomplete="off" required="required">
                @if ($errors->has('user.name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('user.name') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="inputPhone" class="col-sm-4 col-form-label">{{__('Phone')}}</label>
            <div class="col-sm-8">
                <input type="text" id="inputPhone" name="user[phone]" @if(isset($user->phone)) value="{{ $user->phone }}" @endif class="form-control" form="editForm">
                @if ($errors->has('user.phone'))
                    <span class="help-block">
                        <strong>{{ $errors->first('user.phone') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="inputName" class="col-sm-4 col-form-label">{{__('About Me')}}</label>
            <div class="col-sm-8">
                <textarea name="user[description]"  class="form-control about-me" form="editForm">@if(isset($user->description)){{ $user->description }}@endif</textarea>
                @if ($errors->has('user.description'))
                    <span class="help-block">
                        <strong>{{ $errors->first('user.description') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <hr>
        <div class="form-group row">
            <div class="col-12">
                <button type="submit" class="btn btn-green float-right">{{__('Save')}}</button>
            </div>
        </div>
    </form>
    </div>
</div>
@push('styles')
    <link href="{{ asset('/vendor/chernogolov/fogcms/public/css/vendor/imgareaselect/imgareaselect-default.css') }}" rel="stylesheet" >
@endpush
@push('scripts')
        <script src="{{ asset('/vendor/chernogolov/fogcms/public/js/vendor/jquery.imgareaselect.pack.js') }}"></script>
        <script type="text/javascript">
        $(document).ready(function () {

//            var scale = $(".user-avatar").width() * 100 / 80;
//            alert(scale);

//            $('.preview').css({
//                width:'98%'
//                });
//
//            $('.user-avatar').imgAreaSelect({
//                handles: true,
//                aspectRatio: '1:1',
//                x1: 0,
//                y1: 0,
//                x2: $(".user-avatar").width()-1,
//                y2: $(".user-avatar").width()-1,
//                minWidth: 80,
//                minHeight: 80,
//                onSelectChange: preview
//            });
        });

        function preview(img, selection) {
            var scaleX = 100 / (selection.width || 1);
            var scaleY = 100 / (selection.height || 1);

            $('.preview').css({
                width: Math.round(scaleX * 135) + 'px',
                eight: Math.round(scaleY * 80) + 'px',
                marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px',
                marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
            });
        }
        </script>
@endpush

