@if($record->$key == 0)
Новый
@elseif($record->$key == 1)
В работе
@elseif($record->$key == 2)
Выполнен
@endif
