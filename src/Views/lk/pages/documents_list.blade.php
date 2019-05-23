<div class="col-12">
    <div class="container-input mb-4">
       <h1 class="blue mb-5">{{__('Documents')}}</h1>
            <div class="row">
                @foreach($documents as $item)
                    <div class="col-12">
                        <h5 class="mt-0 mb-2 float-lg-left text-center text-lg-left"><strong>{{$item->title}}</strong></h5>
                        <div class="clearfix"></div>
                        <a href="{{$item->document}}" class="btn btn-outline-dark btn-sm mt-2 col-12 col-lg-2 "><span class="mdi mdi-download"></span>&nbsp;{{__('Download')}}</a>
                        <span class="text-success btn btn-sm btn-light float-lg-right mt-2 d-none d-lg-block">{{$item->multiaddress}}</span>
                        <span class="text-success btn btn-sm btn-light float-lg-right mt-2 d-block d-lg-none">{{$item->multiaddress}}</span>
                        <hr>
                    </div>
                @endforeach
            </div>
    </div>
</div>

