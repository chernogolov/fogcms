<div class="col-12">
    <div class="container-input">
       <h1 class="blue">{{__('Edit accounts')}}</h1>
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
        <h5 class="mt-4">
            {{__('Add account')}}
        </h5>
       <form method="post" class="form-horizontal mt-5" id="editForm" enctype="multipart/form-data"
          xmlns="http://www.w3.org/1999/html">
        {{ csrf_field() }}

        <div class="form-group row">
            <label for="inputAccountId" class="col-sm-4 col-form-label">{{__('Account Id')}} <strong class="text-danger">*</strong></label>
            <div class="col-sm-8">
                <input type="text" name="user[account_number]" class="form-control" id="inputAccountId" form="editForm" autocomplete="off" required="required">
                @if ($errors->has('user.account_number'))
                    <span class="help-block">
                        <strong>{{ $errors->first('user.account_number') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="inputAccountPin" class="col-sm-4 col-form-label">{{__('PIN')}} <strong class="text-danger">*</strong></label>
            <div class="col-sm-8">
                <input type="text" name="user[pin]" class="form-control" id="inputAccountPin" form="editForm" autocomplete="off" required="required">
                @if ($errors->has('user.pin'))
                    <span class="help-block">
                        <strong>{{ $errors->first('user.pin') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <div class="col-12 text-right">
                <p>
                   @php $text = \Chernogolov\Fogcms\Records::getRecord(3550)['description']; @endphp
                   {!!$text!!}
                </p>
            </div>
        </div>
        <hr>
        <div class="form-group row">
            <div class="col-12">
                <button type="submit" class="btn btn-green float-right">{{__('Add')}}</button>
            </div>
        </div>
    </form>
       <form method="post" class="form-horizontal mt-5" id="deleteForm" enctype="multipart/form-data" xmlns="http://www.w3.org/1999/html">
            {{ csrf_field() }}
            <h5 class="mt-4">
                {{__('My accounts')}}
            </h5>
            <div class="table-responsive mt-4">
              <table class="table">
                <tr>
                    <th>
                        {{__('Account id')}}
                     </th>
                     <th>
                        {{__('Address')}}
                     </th>
                     <th class="text-right">
                        {{__('Actions')}}
                     </th>
                </tr>
                  @if(isset($records) && !empty($records))
                       @foreach($records as $record)
                           <tr>
                                <td>
                                    {{$record->account_number}}
                                </td>
                                <td>
                                    {{$record->address}} {{__('room')}} {{$record->room}}
                                </td>
                                <td>
                                    <button type="submit" form="deleteForm" class="btn btn-default btn-sm float-right" name="remove-account" value="{{$record->id}}" onclick="return confirm('{{__('Are you sure?!')}}')">{{__('Delete')}}</button>
                                </td>
                           </tr>
                       @endforeach
                  @endif
              </table>
           </div>
       </form>
    </div>
</div>

