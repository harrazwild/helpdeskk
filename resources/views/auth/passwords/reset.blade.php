@extends('layout.publiclayout')

  @section('content')
    <div class="container">
      <div class="row">
        <div class="offset-md-2 col-md-8">
          <div class="sign-in-wrapper">
            <div class="sign-container">
              <div class="text-center">
                <h4 class="text-light">{{ __('Tetapan Semula Katalaluan') }}</h4>
              </div>
              <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group row">
                  <label for="email" class="col-md-4 col-form-label text-md-right text-light">{{ __('Alamat E-Mel') }}</label>
                  <div class="col-md-6">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email">
                    @error('email')
                      <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label for="password" class="col-md-4 col-form-label text-md-right text-light">{{ __('Katalaluan Baru') }}</label>
                  <div class="col-md-6">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                    @error('password')
                      <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>
                <div class="form-group row">
                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right text-light">{{ __('Pengesahan Katalaluan Baru') }}</label>
                    <div class="col-md-6">
                      <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                    </div>
                </div>
                <div class="form-group row">
                  <label for="email" class="col-md-4 col-form-label text-md-right text-light"></label>
                  <div class="col-md-8">
                    <a class="btn btn-custom" href="{{ route('login') }}">{{ __('Kembali') }}</a>
                    <button type="submit" class="btn btn-info">{{ __('Kemaskini Katalaluan') }}</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endsection