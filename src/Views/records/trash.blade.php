<div class="card-body">
    <form method="post" class="form-horizontal" id="trashForm" enctype="multipart/form-data" action="{{ route('trash') }}">
        {{ csrf_field() }}
        <button type="submit" data-destination="records" class="btn btn-default submit" form="trashForm" onclick="return confirm('Внимание! Удаляем все материалы из корзины. Точно?');">Очистить корзину</button>
        <hr>
        <input type="hidden" name="trash" value="clear" form="trashForm">
        <div class="table-responsive">
             <table class="table table-striped">
                <tr>
                    <th>
                        №
                    </th>
                    <th>
                        Статус
                    </th>
                    <th>
                        Пользователь
                    </th>
                </tr>
                @foreach ($records as $record)
                    <tr>
                        <td>
                            {{ $record->id }}
                        </td>
                        <td>
                            {{ $record->status }}
                        </td>
                        <td>
                            {{ $record->user_id }}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>

    </form>
</div>