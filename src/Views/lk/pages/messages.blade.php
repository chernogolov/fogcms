<div class="col-12">
    <div class="container-input mb-4">
    <form method="post" class="form-horizontal mt-4 pb-5" id="messagesForm"
           xmlns="http://www.w3.org/1999/html">
           {{ csrf_field() }}
           <div class="row  mb-5">
                <div class="col-12 col-sm-7"><h1 class="blue float-left mb-2 mb-lg-0">{{__('Messages')}}</h1></div>
                @if(count($user->notifications)>0)
                <div class="col-12 col-sm-5">
                    <button class="btn btn-outline-success float-lg-right" form="messagesForm" type="submit" form="messagesForm" value="true" name="mark-as-read">
                        <span class="mdi mdi-check-all"></span>&nbsp;
                        {{__('Mark as read all')}}
                    </button>
               </div>
               @endif
           </div>
           <div class="clearfix"></div>
           @if(count($user->notifications)>0)
               <div class="row">
                    @foreach($user->notifications()->paginate(20) as $note)
                        <div class="col-12 mb-3">
                            <div class="media message pt-2 pb-3 pl-3 pr-0 pr-lg-3 @if(!$note->read_at) unread @endif">
                              <div class="media-body">
                                <h3 class="mb-2">
                                    @isset($note->data['theme']){{$note->data['theme']}}@endisset
                                </h3>
                                @isset($note->data['message']){{$note->data['message']}}@endisset
                                <div class="clearfix"></div>
                                <br>
                                @isset($note->data['action'])<a class="btn btn-outline-dark btn-sm" href="{{$note->data['action']}}">Подробнее</a>@endisset
                                <div class="mt-3 mt-lg-0 float-lg-right text-lg-right">
                                    <small class="text-muted">{{__('Created at')}} : {{$note->created_at}}</small><br>
                                    @if($note->read_at)<small class="text-muted">{{__('Read at')}} : {{$note->read_at}}</small>@endif
                                </div>
                              </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="col-12">
                        {{$user->notifications()->paginate(20)->links()}}
                    </div>
                </div>
                <div class="float-lg-right">
                    <button class="btn btn-outline-success float-lg-right mt-3" form="messagesForm" type="submit" value="true" name="mark-as-read">
                        <span class="mdi mdi-check-all"></span>&nbsp;
                        {{__('Mark as read all')}}
                    </button>
                  </div>
                  <div class="float-lg-left">
                      <button class="btn btn-outline-dark float-lg-left mt-5 mt-lg-3 mr-lg-3" form="messagesForm" type="submit" value="true" name="delete-all" onclick="return confirm('{{__('Confirm delete')}}');" >
                          <span class="mdi mdi-delete"></span>&nbsp;
                          {{__('Delete all')}}
                      </button>
                  </div>
              @else
                {{__('No messages')}}
              @endif
       </form>
    </div>
</div>

