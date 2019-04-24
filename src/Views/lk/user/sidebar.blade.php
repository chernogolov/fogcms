<div class="slidebar-left d-flex flex-column justify-content-between mt-4">
    <img src="/img/tune.jpg" class="img-fluid d-none d-lg-block">
    <div class="object-selector d-none d-lg-block">
        {{__('Set up your profile!')}}
    </div>
    <ul class="list-group mt-4">
      <li class="list-group-item @if (Route::currentRouteName() == 'lk-options') active @endif">
        <a href="{{route('lk-profile')}}" >{{__('Profile')}}</a></li>
      <li class="list-group-item @if (Route::currentRouteName() == 'lk-accounts') active @endif">
        <a href="{{route('lk-accounts')}}">{{__('Accounts')}}</a>
      </li>
      <li class="list-group-item @if (Route::currentRouteName() == 'lk-notifications') active @endif">
          <a href="{{route('lk-notifications')}}">{{__('Notifications')}}</a>
      </li>
      <li class="list-group-item @if (Route::currentRouteName() == 'lk-password') active @endif">
        <a href="{{route('lk-password')}}">{{__('Password')}}</a>
      </li>
    </ul>
    <div class="logout mt-5 d-none d-lg-block">
        <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();" href="{{ route('logout') }}" class="d-flex justify-content-between"><span>Выход из системы</span><img src="{{ asset('/vendor/fogcms/img/exit_btn.png') }}" alt="exit"></a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</div>
