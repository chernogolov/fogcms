<div class="col-12">
    <div class="container-input mb-4">
        <div class="row">
            <div class="col-12 col-lg-6">
                <h1 class="blue mb-2">{{__('Ticket')}} # {{ $data->id }}</h1>
            </div>
            <div class="col-12 col-lg-6">
                <div class="row mb-2 mt-lg-0 mt-3">
                    <div class="col-12 text-lg-right">
                        <span class="text-muted">{{__('Created at')}}:</span> {{ $data->created_at }}
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-12  text-lg-right">
                        <span class="text-muted">{{__('Updated at')}}:</span> {{ $data->updated_at }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12 ">
                <span class="text-muted">{{__('Status')}}:</span>
                <span class="label @if($data->status == 1) label-danger @elseif ($data->status == 2) label-warning @elseif ($data->status == 3) label-success @elseif ($data->status == 4) label-default @endif">
                    @if($data->status == 1) ОТКРЫТАЯ @elseif ($data->status == 2) В РАБОТЕ @elseif ($data->status == 3) ВЫПОЛНЕНО @elseif ($data->status == 4) ЗАКРЫТО АВТОРОМ @endif
                </span>
            </div>
        </div>
        <hr>
        <h2 class="mb-3">
            {{__('Ticket details')}}
        </h2>
