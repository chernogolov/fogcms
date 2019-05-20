<div class="col-12">
    <div class="container-input">
       <h1 class="blue mb-5">{{__('Contact book')}}</h1>
            <div class="row">
                @foreach($contacts as $item)
                    <div class="col-12">
                        <div class="media">
                         <a href="{{route('contact', ['id' => $item->id])}}">
                              <img class="mr-3 img-fluid rounded-circle" src="@if(isset($item->photo) && strlen($item->photo)>0)/imagecache/avatar/{{$item->photo}}@else /img/default-user-avatar.jpg @endif" alt="login">
                         </a>
                          <div class="media-body">
                            <h5 class="mt-0 mb-2 float-lg-left"><strong><a href="{{route('contact', ['id' => $item->id])}}">{{$item->fio}}</a></strong></h5>
                            <small class="float-lg-right">{{$item->post}}</small><br>
                            @if(isset($item->phone) && strlen($item->phone)>0)
                                <div class="mt-2">
                                    <span class="mdi mdi-phone"></span>&nbsp;<a href="tel:{{$item->phone}}">{{$item->phone}}</a>
                                </div>
                            @endif
                            @if(isset($item->email) && strlen($item->email)>0)
                                <div class="mt-2">
                                    <span class="mdi mdi-email"></span>&nbsp;<a href="mailto:{{$item->email}}">{{$item->email}}</a>
                                </div>
                            @endif
                            <a class="btn btn-sm btn-outline-dark mt-2 d-none d-lg-inline-block" href="{{route('contact', ['id' => $item->id])}}"><span class="mdi-account-card-details mdi"></span>&nbsp;{{__('More')}}</a>
                            <span class="text-success btn btn-sm btn-light float-right mt-2 d-none d-lg-block">{{$item->multiaddress}}</span>
                          </div>
                        </div>
                        <a class="btn btn-sm btn-outline-dark mt-2 d-inline-block d-lg-none" href="{{route('contact', ['id' => $item->id])}}"><span class="mdi-account-card-details mdi"></span>&nbsp;{{__('More')}}</a>
                        <span class="text-success btn btn-sm btn-light float-right mt-2 d-block d-lg-none">{{$item->multiaddress}}</span>
                        <hr>
                    </div>
                @endforeach
            </div>
    </div>
</div>

