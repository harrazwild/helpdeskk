@extends('layout.publiclayout')

@section('content')
<style>
hr
{
    margin-top: 6px;
    margin-bottom: 6px;
}
</style>

<script type="text/javascript">
$(document).ready(function(){
  var department = $('#department_id').val();

  $('.sector').on('change',function(e) {
    var id = $(this).val();

    if(id){
      var url = '{{ route("getDepartments", ":id") }}';
      url = url.replace(':id', id );
      $.ajax({
        type:"GET",
        url: url,
        success:function(res){        
        //console.log(res);
          if(res){
            $(".department").empty();
            $(".department").append('<option value="">Sila Pilih</option>');
            $.each(res, function(key, value) {
              $(".department").append('<option value="'+ value.department_code +'">'+ value.department_desc +'</option>');
            });
          }else{
            $(".department").empty();
            $(".department").append('<option value="">Sila Pilih</option>');
          }
        }
      });
    }else{
      $(".department").empty();
      $(".department").append('<option value="">Sila Pilih Sektor</option>');
    }
  });

});
</script>

<div class="container">

  <div class="sign-in-wrapper">
    <div class="sign-container">
      <div class="text-center">
        <h4 class="text-light">{{ __('Daftar Akaun Pengguna') }}</h4>
      </div>
      <form class="form-horizontal" method="POST" action="{{ route('save_new_user') }}">
        @csrf

        <div class="form-group row">
          <label for="email" class="col-md-2 col-form-label text-md-right text-light">No Kad Pengenalan</label>
          <div class="col-md-4">
            <input class="form-control @error('ic_number') is-invalid @enderror" name="ic_number" type="text" value="{{ old('ic_number') }}" maxlength="12" onkeypress="return inputLimiter(event,'Numbers')">
            @error('ic_number')
            <label id="name-error" class="error" for="ic_number">{{$message}}</label>
            @enderror
          </div>
        </div>
        <div class="form-group row">
          <label for="password" class="col-md-2 col-form-label text-md-right text-light">Nama</label>
          <div class="col-md-8">
            <input class="form-control @error('name') is-invalid @enderror" name="name" type="text" value="{{ old('name') }}">
            @error('name')
            <label id="name-error" class="error" for="name">{{$message}}</label>
            @enderror
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group row">
              <label class="col-sm-6 col-form-label text-md-right text-light">Gred</label>
              <div class="col-lg-6">
                <select class="form-control @error('grade_id') is-invalid @enderror" name="grade_id" id="grade_id">
                  <option value="">Sila Pilih</option>
                  @foreach($grades as $row)
                  <option value="{{ $row->grade_code }}" {{ old('grade_id') == $row->grade_code ? 'selected' : '' }} >{{ $row->grade_desc }}</option>
                  @endforeach
                </select>
                @error('grade_id')
                <label id="name-error" class="error" for="grade_id">{{$message}}</label>
                @enderror
              </div>
            </div>
          </div>
          <div class="col-sm-6">
              <div class="form-group row">
                <label class="col-sm-4 col-form-label text-md-right text-light">Jawatan</label>
                <div class="col-lg-8">
                  <select class="form-control @error('position_id') is-invalid @enderror" name="position_id" id="position_id">
                    <option value="">Sila Pilih</option>
                    @foreach($positions as $row)
                    <option value="{{ $row->position_code }}" {{ old('position_id') == $row->position_code ? 'selected' : '' }} >{{ $row->position_desc }}</option>
                    @endforeach
                  </select>
                  @error('position_id')
                  <label id="name-error" class="error" for="position_id">{{$message}}</label>
                  @enderror
                </div>
              </div>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label text-md-right text-light">Sektor</label>
          <div class="col-lg-4">
            <select name="sector_id" id="sector_id" class="form-control sector @error('sector_id') is-invalid @enderror">
              <option value="">Sila Pilih</option>
              @foreach($sectors as $row)
              <option value="{{ $row->sector_code }}" {{ old('sector_id') == $row->sector_code ? 'selected' : '' }} >{{ $row->sector_desc }}</option>
              @endforeach
            </select>
            @error('sector_id')
            <label id="name-error" class="error" for="sector_id">{{$message}}</label>
            @enderror
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label text-md-right text-light">Bahagian</label>
          <div class="col-lg-8">
            <select name="department_id" id="department_id" class="form-control department @error('department_id') is-invalid @enderror">
              <option value="">Sila Pilih</option>
              @foreach($departments as $row)
              <option value="{{ $row->department_code }}" {{ old('department_id') == $row->department_code ? 'selected' : '' }} >{{ $row->department_desc }}</option>
              @endforeach
            </select>
            @error('department_id')
            <label id="name-error" class="error" for="department_id">{{$message}}</label>
            @enderror
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group row">
              <label class="col-sm-6 col-form-label text-md-right text-light">Blok</label>
              <div class="col-lg-6">
                <select name="block" id="block" class="form-control @error('block') is-invalid @enderror">
                  <option value="">Sila Pilih</option>
                  <option value="F2" {{ old('block') == 'F2' ? 'selected' : '' }} >Blok F2</option>
                  <option value="F3" {{ old('block') == 'F3' ? 'selected' : '' }} >Blok F3</option>
                </select>
                @error('block')
                <label id="name-error" class="error" for="block">{{$message}}</label>
                @enderror
              </div>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group row">
              <label class="col-sm-4 col-form-label text-md-right text-light">Aras</label>
              <div class="col-lg-8">
                <select name="level" id="level" class="form-control @error('level') is-invalid @enderror">
                  <option value="">Sila Pilih</option>
                  <option value="L1" {{ old('level') == 'L1' ? 'selected' : '' }} >Aras 1</option>
                  <option value="L2" {{ old('level') == 'L2' ? 'selected' : '' }} >Aras 2</option>
                  <option value="L3" {{ old('level') == 'L3' ? 'selected' : '' }} >Aras 3</option>
                  <option value="L4" {{ old('level') == 'L4' ? 'selected' : '' }} >Aras 4</option>
                  <option value="L5" {{ old('level') == 'L5' ? 'selected' : '' }} >Aras 5</option>
                  <option value="L6" {{ old('level') == 'L6' ? 'selected' : '' }} >Aras 6</option>
                  <option value="L7" {{ old('level') == 'L7' ? 'selected' : '' }} >Aras 7</option>
                  <option value="L8" {{ old('level') == 'L8' ? 'selected' : '' }} >Aras 8</option>
                  <option value="L9" {{ old('level') == 'L9' ? 'selected' : '' }} >Aras 9</option>
                </select>
                @error('level')
                <label id="name-error" class="error" for="level">{{$message}}</label>
                @enderror
              </div>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group row">
              <label class="col-sm-4 col-form-label text-md-right text-light">Zon</label>
              <div class="col-lg-8">
                <select name="zone" id="zone" class="form-control @error('zone') is-invalid @enderror">
                  <option value="">Sila Pilih</option>
                  <option value="A" {{ old('zone') == 'A' ? 'selected' : '' }} >Zon A</option>
                  <option value="B" {{ old('zone') == 'B' ? 'selected' : '' }} >Zon B</option>
                </select>
                @error('zone')
                <label id="name-error" class="error" for="zone">{{$message}}</label>
                @enderror
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group row">
              <label class="col-sm-4 col-form-label text-md-right text-light">No Telefon (Pejabat)</label>
              <div class="col-lg-4">
                <input class="form-control @error('telephone') is-invalid @enderror" name="telephone" type="text" value="{{ old('telephone') }}" maxlength="11" onkeypress="return inputLimiter(event,'Numbers')">
                @error('telephone')
                <label id="name-error" class="error" for="telephone">{{$message}}</label>
                @enderror
              </div>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group row">
              <label class="col-sm-6 col-form-label text-md-right text-light">No Telefon (Peribadi)</label>
              <div class="col-lg-6">
                <input class="form-control @error('handphone') is-invalid @enderror" name="handphone" type="text" value="{{ old('handphone') }}" maxlength="11" onkeypress="return inputLimiter(event,'Numbers')">
                @error('handphone')
                <label id="name-error" class="error" for="handphone">{{$message}}</label>
                @enderror
              </div>
            </div>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label text-md-right text-light">Emel</label>
          <div class="col-lg-8">
            <input class="form-control @error('email') is-invalid @enderror" name="email" type="text" value="{{ old('email') }}">
            @error('email')
            <label id="name-error" class="error" for="email">{{$message}}</label>
            @enderror
          </div>
        </div>

        <div class="form-group row">
          <label for="email" class="col-md-2 col-form-label text-md-right text-light"></label>
          <div class="col-md-8">
            <a class="btn btn-custom" href="{{ route('login') }}">{{ __('Kembali') }}</a>
            <button type="submit" class="btn btn-info">{{ __('Daftar') }}</button>
          </div>
        </div>
      </form>
    </div>
  </div>

</div>
@endsection