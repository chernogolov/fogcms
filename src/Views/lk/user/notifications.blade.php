<div class="col-12">
    <div class="container-input">
        <h1 class="blue">{{__('Edit notifications')}}</h1>
        <div class="row mt-4">
            <div class="col-12">
                @if(isset($user_errors) && is_array($user_errors))
                    @foreach($user_errors as $uerror)
                        <div class="alert alert-danger">
                            {{$uerror}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endforeach
                @endif
                @if(isset($user_messages) && is_array($user_messages))
                    @foreach($user_messages as $umessage)
                        <div class="alert alert-success">
                            {{$umessage}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        <form method="post" class="form-horizontal mt-4 pb-5" id="editForm" enctype="multipart/form-data"
              xmlns="http://www.w3.org/1999/html">
            {{ csrf_field() }}
                @foreach($nodes as $node)
                    <div class="row">
                        <div class="col-3">
                            {{__('Setup')}}
                        </div>
                        <div class="col-3 text-center d-none d-lg-block">
                            {{__('Kabinet')}}
                        </div>
                        <div class="col-3 text-center d-none d-lg-block">
                            {{__('Email')}}
                        </div>
                        <div class="col-3 text-center d-none d-lg-block">
                            {{__('Push')}}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12 col-lg-3 pl-lg-4 mb-4 mb-lg-0">
                            <label for="send_{{ $node->id }}" ><h3 class="mt-2">{{$node->name}}</h3></label>
                        </div>
                        <div class="col-12 col-lg-3 text-lg-center">
                            <span class="d-inline-block d-lg-none">{{__('Kabinet')}}</span>
                            <label class="switch switch-left-right float-right float-lg-none">
                                <input class="switch-input" type="checkbox" checked disabled>
                                <span class="switch-label disabled" data-on="Да" data-off="Нет"></span><span class="switch-handle"></span>
                            </label>
                        </div>
                        <div class="col-12 col-lg-3 text-lg-center">
                            <span class="d-inline-block d-lg-none">{{__('Email')}}</span>
                            <label class="switch switch-left-right float-right float-lg-none">
                                @if(isset($access[$node->id]) && $access[$node->id]->email == 1)
                                    <input type="hidden" name="regs[{{ $node->id }}][email]" value="1" id="h_email_{{ $node->id }}">
                                    <input class="switch-input checkbox set-access" type="checkbox" data-id="email_{{ $node->id }}" checked id="email_{{ $node->id }}">
                                @else
                                    <input type="hidden" name="regs[{{ $node->id }}][email]" value="0" id="h_email_{{ $node->id }}">
                                    <input class="switch-input checkbox set-access" type="checkbox" data-id="email_{{ $node->id }}" id="email_{{ $node->id }}">
                                @endif
                                <span class="switch-label" data-on="Да" data-off="Нет"></span><span class="switch-handle"></span>
                            </label>
                        </div>
                        <div class="col-12 col-lg-3 text-lg-center">
                            <span class="d-inline-block d-lg-none">{{__('Push')}}</span>
                            <label class="switch switch-left-right float-right float-lg-none">
                                @if(isset($access[$node->id]) && $access[$node->id]->push == 1)
                                    <input type="hidden" name="regs[{{ $node->id }}][push]" value="1" id="h_push_{{ $node->id }}">
                                    <input class="switch-input checkbox set-access" type="checkbox" data-id="push_{{ $node->id }}" checked id="push_{{ $node->id }}">
                                @else
                                    <input type="hidden" name="regs[{{ $node->id }}][push]" value="0" id="h_push_{{ $node->id }}">
                                    <input class="switch-input checkbox set-access" type="checkbox" data-id="push_{{ $node->id }}" id="push_{{ $node->id }}">
                                @endif
                                <span class="switch-label" data-on="Да" data-off="Нет"></span><span class="switch-handle"></span>
                            </label>
                        </div>
                    </div>
                @endforeach
                <hr class="mb-4">
            <button type="submit" class="btn btn-green float-right">{{__('Save')}}</button>
        </form>
    </div>
</div>
