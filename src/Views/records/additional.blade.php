    <div id="comments">
        @if(isset($comments))
            {!! $comments !!}
        @endif
    </div>
    @if(!empty($creator))
        <hr>
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link active" href="#creator" data-toggle="tab">{{__('Author')}}</a></li>
            <li class="nav-item"><a class="nav-link" href="#creator-history" data-toggle="tab">{{__('Author history')}}</a></li>
            <li class="nav-item"><a class="nav-link" href="#history" data-toggle="tab">{{__('Status history')}}</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="creator">
                <div class="table-container table-responsive">
                    <table class="table">
                    <tr>
                        <td>
                            {{__('Fio')}}
                        </td>
                        <td>
                            <strong>{{ $creator['name'] }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{__('Email')}} @if(isset($creator['email']->value) && $creator['login'] != $creator['email']->value)(осн / доп)@endif
                        </td>
                        <td>
                            <strong>
                                <a href="mailto:{{ $creator['email'] }}">
                                    {{ $creator['email'] }}
                                </a>
                                @if(isset($creator['email']->value) && $creator['login'] != $creator['email']->value)
                                    / {{ $creator['email']->value }}
                                @endif
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{__('Phone')}}
                        </td>
                        <td>
                            <strong>
                                <a href="tel:{{ $creator['phone']}}">{{ $creator['phone']}}</a>
                            </strong>
                        </td>
                    </tr>
                </table>
                </div>
            </div>
            <div class="tab-pane" id="creator-history">
                <div class="table-container table-responsive">
                    @include('fogcms::records.additional.tickets')
                </div>
            </div>
            <div class="tab-pane" id="history">
                <div class="table-container table-responsive">
                    <table class="table">
                    <tr>
                        <th>
                            {{__('User')}}
                        </th>
                        <th>
                            {{__('Status')}}
                        </th>
                        <th>
                            {{__('Time')}}
                        </th>
                    </tr>
                    @foreach($history as $item)
                        <tr class="@if($item->status == 1) danger @elseif ($item->status == 2) warning @elseif ($item->status == 3) success @endif">
                            <td>
                                {{ $item->name }}
                            </td>
                            <td>
                                @if($item->status == 1) Открыл @elseif ($item->status == 2) Взял в работу @elseif ($item->status == 3) Выполнил @endif
                            </td>
                            <td>
                                {{ $item->added_on }}
                            </td>
                        </tr>
                    @endforeach
                </table>
                </div>
            </div>
        </div>
    @endif
</div>

