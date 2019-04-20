<div class="col-12">
        @foreach($messages as $message)
            <div class="alert alert-danger">
                {!!$message!!}
                <button type="button" class="close" title="{{__('Close')}}" data-dismiss="alert" aria-hidden="true">&times;</button>
            </div>
        @endforeach
</div>