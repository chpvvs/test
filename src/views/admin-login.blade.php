@extends('admin::layouts.wrapper')
@section('page')
<style type="text/css" media="screen">
  #new_password_form { display: none; }
</style>
<div class="login_bg">
  <div class="login">
    <div class="login__block">
      <a class='login__logo' href='{{ URL::to('/') }}'>
        <div class="login__logo-text">
          OneTouch
        </div>
        <div class="login__logo-image">
          <img class="login__logo-image login__logo-image--img" src='{{ asset('public/demos/admin/img/logo_admin.png') }}' alt="logo" title="logo"/>
        </div>
      </a>
      <h2 class='login__welcome'>@lang('admin::main.login.welcome')</h2>
      <h3 class='login__introduce'>@lang('admin::main.login.introduce')</h3>
      <div class="form-block">
        @if ($errors->has('email'))
          <div class="form-block__error">
            {{ $errors->first('email') }}
          </div>
        @endif
        @if (Session::has('login_success'))
          <div class="form-block__success">
            {{ Session::get('login_success') }}
          </div>
        @endif
        <form class="form-block__form" id="login_form" role="form" method="POST" action="{{ route('admin.login.submit') }}">
          {{ csrf_field() }}
          <div class="form-block__title">
            @lang('admin::main.login.enter')
          </div>
          <div class='form-block__login-container'>
            <div class="form-block__row {{ $errors->has('login') ? 'has-error' : '' }}">
              <input placeholder='@lang('admin::main.login.login')' id="login" type="text" class="form-block__input" name="login" value="{{ old('login') }}" autofocus>
              @if ($errors->has('login'))
                <div class="form-block__help">
                  {{ $errors->first('login') }}
                </div>
              @endif
            </div>
            <div class="form-block__row {{ $errors->has('password') ? 'has-error' : '' }}">
              <input placeholder='@lang('admin::main.login.password')' id="password" type="password" class="form-block__input" name="password">
              @if ($errors->has('password'))
                <div class="form-block__help">
                  {{ $errors->first('password') }}
                </div>
              @endif
            </div>
          </div>
            <div class="form-block__col">
              <div class="form-block__checkbox">
                <label for="remember">
                  @lang('admin::main.login.remember_me')
                </label>
                <input id="remember" class="form-block__remember" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
              </div>
              <a class="form-block__forgot-password" href="#">@lang('admin::main.login.forgot_password')</a>
            </div>
          <div class="form-block__bottom">
            <button type="submit" class="form-block__btn">@lang('admin::main.login.do_login')</button>
          </div>
        </form>
        <form class="form-block__form" id="new_password_form" role="form" method="POST" action="{{ route('admin.login.password-reset') }}">
            {{ csrf_field() }}
            <div class="form-block__title">
              @lang('admin::main.login.forgot_password')
            </div>
            <div class='form-block__login-container'>
                <div class="form-block__row">
                    <input placeholder='@lang('admin::main.login.email')' id="email" type="text" class="form-block__input" name="email">
                </div>
            </div>
            <div class="form-block__bottom">
              <button type="submit" class="form-block__btn form-block__btn--mb">@lang('admin::main.login.send_new_password')</button>
              <a class="form-block__btn cancel_new_password" href='#'>@lang('admin::main.cancel')</a>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
@stop
