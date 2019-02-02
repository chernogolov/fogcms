<div class="row">
    <div class="col-sm-12">
        @for($a = 0;$a<count($attrs);++$a)
            {{ $attrs[$a]->name }}
        @endfor
    </div>
</div>
