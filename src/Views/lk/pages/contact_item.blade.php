<div class="col-12">
    <div class="container-input">
            <div class="row">
                <div class="col-12">
                     @if(isset($item['photo']) && strlen($item['photo'])>0)
                        <img class="float-left mr-3 mb-3" src="/imagecache/medium/{{$item['photo']}}" alt="login">
                     @endif
                     <h1 class="blue mb-3">{{$item['fio']}}</h1>
                     <small>{{$item['post']}}</small>
                     @if(isset($item['phone']) && strlen($item['phone'])>0)
                         <div class="mt-3 mb-2">
                             <span class="mdi mdi-phone"></span>&nbsp;<a href="tel:{{$item['phone']}}">{{$item['phone']}}</a>
                         </div>
                     @endif
                     @if(isset($item['email']) && strlen($item['email'])>0)
                         <div class="mt-3 mb-2">
                             <span class="mdi mdi-email"></span>&nbsp;<a href="mailto:{{$item['email']}}">{{$item['email']}}</a>
                         </div>
                     @endif
                     <div class="mt-3 mb-4">
                        {!!$item['contact']!!}
                     </div>
                     <a class="btn btn btn-outline-dark mt-2 float-sm-right" href="{{route('contacts')}}">{{__('Contact book')}}</a>
                </div>
            </div>
    </div>
</div>

