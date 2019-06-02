@php
    $extensions = ['xls' => 'excel', 'xlsx' => 'excel', 'doc' => 'word', 'docx' => 'word', 'pdf' => 'pdf', 'jpg' => 'image',
    'jpeg' => 'image', 'png' => 'image', 'gif' => 'image']
@endphp

<div class="col-12">
    <div class="container-input mb-4">
       <h1 class="blue mb-5">@isset($node->name){{$node->name}}@endisset</h1>
          <div class="row">
             @foreach($documents as $item)
                <div class="col-12">
                    <h5 class="mb-3">
                        {{$item->multiaddress}}
                    </h5>
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-sm-6 col-12">{{$item->title}}</div>
                        <div class="col-sm-3 col-7 text-left text-sm-right pt-1">
                            {{date('Y-m-d', $item->Date)}}
                        </div>
                        <div class="col-sm-1 col-2 pt-1">
                            @if(isset($extensions[pathinfo($item->document)['extension']]))<span title="{{pathinfo($item->document)['extension']}}" class="text-success float-right mdi-24px mdi mdi-file-{{$extensions[pathinfo($item->document)['extension']]}}-box"></span>@else{{pathinfo($item->document)['extension']}}@endif
                        </div>
                        <div class="col-sm-2 col-3">
                            <a href="{{$item->document}}" class="btn btn-outline-dark btn-sm"><span class="mdi mdi-download"></span>&nbsp;{{__('Download')}}</a>
                        </div>
                    </div>
                    <hr>
                </div>
             @endforeach
          </div>
    </div>
</div>

