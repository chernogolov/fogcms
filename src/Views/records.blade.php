@push('scripts')
        <script src="{{ asset('/vendor/chernogolov/fogcms/public/js/vendor/multiple-select/multiple-select.js') }}"></script>
@endpush

@extends('fogcms::layouts.fog')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3 col-sm-4">
            <div class="card" id="regs">
                <div class="card-header">
                    Журналы
                    <a class="float-right" data-toggle="collapse" data-parent="#regs" href="#collapseOne">
                        <span class="fas fa-minus-square"></span>
                    </a>
                </div>
                <div id="collapseOne">
                    <div class="card-body">
                        @foreach ($views['regs'] as $vd)
                        {!! $vd !!}
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9 col-sm-8">
            <div class="card" id="records">@foreach ($views['records'] as $vd2){!! $vd2 !!}@endforeach</div>
        </div>
    </div>
</div>

<script nonce="{{ csp_nonce() }}" >
    $(function()
    {
        var c = document.cookie;
        $('.collapse').each(function () {
            if (this.id) {
                var pos = c.indexOf(this.id + "_collapse_in=");
                if (pos > -1) {
                    c.substr(pos).split('=')[1].indexOf('false') ? $(this).addClass('in') : $(this).removeClass('in');
                }
            }
        }).on('hidden.bs.collapse shown.bs.collapse', function () {
            if (this.id) {
                document.cookie = this.id + "_collapse_in=" + $(this).hasClass('in');
            }
        });
    });
</script>
@endsection
