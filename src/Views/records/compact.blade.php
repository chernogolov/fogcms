<ul class="nav-regs-compact">
    @for ($n = 0;$n< count($nodes); $n++)
    <li @if (Route::currentRouteName() == $nodes[$n]) class="active" @endif >
        <a href="{{ route('regs') . '/' . $nodes[$n]->id }}">
            @if(isset($nodes[$n]->image) && $nodes[$n]->image != '')
                <img src="{{ $nodes[$n]->image }}" class="img-responsive" title="{{ $nodes[$n]->name }}">
            @else
                <img src="/img/panel/q.png" class="img-responsive" title="Загрузите иконку для {{ $nodes[$n]->name }}">
            @endif
        </a>
    </li>
    @endfor
    <li>
        &nbsp;
    </li>
    <li @if (Route::currentRouteName() == 'trash') class="active" @endif >
    <a href="{{ route('trash') }}">
        <img src="/img/panel/trash.png" class="img-responsive" title="Корзина">
    </a>
    </li>
</ul>