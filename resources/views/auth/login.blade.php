@extends('layout.publiclayout')

  @section('content')
    <script>
      function onSubmit(token) {
        $("#sign-in").submit();
      }
    </script>

    <div class="container">
      <div class="row">
        <div class="col-md-4 text-center">
          {{-- <img src="{{ asset('img/Info.png') }}" width="100%"> --}}
        </div>
        <div class="col-md-4">
          <div class="sign-in-wrapper">
            <div class="sign-container">
              <div class="text-center">
                <h4 class="text-light">Log Masuk</h4>
              </div>
              @if (session('message'))
                <div class="alert alert-danger font-medium text-sm">{{ session('message') }}</div>
              @endif
              @if (session('success'))
                <div class="alert alert-success font-medium text-sm">{{ session('success') }}</div>
              @endif
              <form class="sign-in-form" id="sign-in" method="POST" action="{{ route('login') }}" autocomplete="off">
                @csrf
                <div class="form-group">
                  <input type="text" class="form-control" placeholder="ID Pengguna" name="ic_number" autofocus maxlength="12" onkeypress="return inputLimiter(event,'Numbers')">
                  @error('ic_number')
                  <label id="name-error" class="error" for="ic_number">{{$message}}</label>
                  @enderror
                </div>
                <div class="form-group">
                  <input type="password" class="form-control" placeholder="Katalaluan" name="password" autocomplete="off">

                  @error('password')
                  <label id="name-error" class="error" for="password">{{$message}}</label>
                  @enderror

                  @error('g-recaptcha-response')
                  <label id="name-error" class="error" for="g-recaptcha-response">{{$message}}</label>
                  @enderror
                </div>
                @if(config('recaptchav3.sitekey'))
                  {!! RecaptchaV3::field('login') !!}
                @endif
                <button type="submit" class="btn btn-info btn-block">Log Masuk</button>
                <a class="btn btn-custom btn-block" href="{{ route('password.request') }}">{{ __('Lupa Katalaluan?') }}</a>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endsection