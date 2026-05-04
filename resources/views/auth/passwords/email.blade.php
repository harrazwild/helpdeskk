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
              @if (session('status'))
                <div class="alert alert-success" role="alert">{{ session('status') }}</div>
              @endif
              <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-group row">
                  <label for="email" class="col-md-4 col-form-label text-md-right text-light">{{ __('Alamat E-Mel') }}</label>
                  <div class="col-md-8">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label for="email" class="col-md-4 col-form-label text-md-right text-light"></label>
                  <div class="col-md-8">
                    <a class="btn btn-custom" href="{{ route('login') }}">{{ __('Kembali') }}</a>
                    <button type="submit" class="btn btn-info">{{ __('Hantar') }}</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endsection