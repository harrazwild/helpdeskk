@extends('layout.userlayout')

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

                //$('.department option[value="'+categories.section_id+'"]').prop('selected', true);

              }else{
                $(".department").empty();
                $(".department").append('<option value="">Sila Pilih</option>');
              }
            }
          });
        }else{
          $(".department").empty();
          $(".department").append('<option value="">Sila Pilih</option>');
        }
    });
});
</script>
<div class="ui-content-body">  
    <div class="ui-container">

        <div class="row">
            <div class="col-sm-12">
                <form class="form-horizontal" method="post" action="{{ route('upd_profile', Crypt::encrypt($user->id)) }}">
                @csrf
                @method('PUT')
                <section class="panel panel-default">
                    <div class="panel-heading">Kemaskini Profil</div>
                    <div class="panel-body table-responsive">

                        <div class="form-group">
                            <label class=" col-sm-2 control-label">No Kad Pengenalan</label>
                            <div class="col-lg-2">
                                <input class="form-control @error('ic_number') is-invalid @enderror" name="ic_number" type="text" value="{{ $user->ic_number }}">
                                @error('ic_number')
                                <label id="name-error" class="error" for="ic_number">{{$message}}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class=" col-sm-2 control-label">Nama</label>
                            <div class="col-lg-6">
                                <input class="form-control @error('name') is-invalid @enderror" name="name" type="text" value="{{ $user->name }}">
                                @error('name')
                                <label id="name-error" class="error" for="name">{{$message}}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class=" col-sm-6 control-label">Gred</label>
                                    <div class="col-lg-4">
                                        <select class="form-control @error('gred') is-invalid @enderror" name="gred" id="gred">
                                            <option value="">Sila Pilih</option>
                                            @foreach($gred as $row)
                                            <option value="{{ $row->grade_code }}" {{ $user->gred_code == $row->grade_code ? 'selected' : '' }} >{{ $row->grade_desc }}</option>
                                            @endforeach
                                        </select>
                                        @error('gred')
                                        <label id="name-error" class="error" for="gred">{{$message}}</label>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class=" col-sm-2 control-label">Jawatan</label>
                                    <div class="col-lg-6">
                                        <select class="form-control @error('jawatan') is-invalid @enderror" name="jawatan" id="jawatan">
                                            <option value="">Sila Pilih</option>
                                            @foreach($jawatan as $row)
                                            <option value="{{ $row->position_code }}" {{ $user->jaw_code == $row->position_code ? 'selected' : '' }} >{{ $row->position_desc }}</option>
                                            @endforeach
                                        </select>
                                        @error('jawatan')
                                        <label id="name-error" class="error" for="jawatan">{{$message}}</label>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>  
                        <div class="form-group">
                            <label class=" col-sm-2 control-label">Sektor</label>
                            <div class="col-lg-3">
                                <select name="sector" id="sector" class="form-control sector @error('sector') is-invalid @enderror">
                                    <option value="">Sila Pilih</option>
                                    @foreach($sectors as $row)
                                    <option value="{{ $row->sector_code }}" {{ $user->sector_code == $row->sector_code ? 'selected' : '' }} >{{ $row->sector_desc }}</option>
                                    @endforeach
                                </select>
                                @error('sector')
                                <label id="name-error" class="error" for="sector">{{$message}}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class=" col-sm-2 control-label">Bahagian</label>
                            <div class="col-lg-6">
                                <select name="department" id="department" class="form-control department @error('department') is-invalid @enderror">
                                    <option value="">Sila Pilih</option>
                                    @foreach($departments as $row)
                                    <option value="{{ $row->department_code }}" {{ $user->department_code == $row->department_code ? 'selected' : '' }} >{{ $row->department_desc }}</option>
                                    @endforeach
                                </select>
                                @error('department')
                                <label id="name-error" class="error" for="department">{{$message}}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-4">
                            <div class="form-group">
                              <label for="block" class="col-sm-6 control-label">Blok</label>
                              <div class="col-sm-4">
                                <select name="block" id="block" class="form-control block @error('block') is-invalid @enderror">
                                  <option value="">Sila Pilih</option>
                                  <option value="F2" {{ Auth::user()->block == 'F2' ? 'selected' : '' }} >F2</option>
                                  <option value="F3" {{ Auth::user()->block == 'F3' ? 'selected' : '' }} >F3</option>
                                </select>
                                @error('block')
                                <label id="name-error" class="error" for="block">{{$message}}</label>
                                @enderror
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="form-group">
                              <label for="level" class="col-sm-2 control-label">Aras</label>
                              <div class="col-sm-7">
                                <select name="level" id="level" class="form-control level @error('level') is-invalid @enderror">
                                  <option value="">Sila Pilih</option>
                                  <option value="L1" {{ Auth::user()->level == 'L1' ? 'selected' : '' }} >Aras 1</option>
                                  <option value="L2" {{ Auth::user()->level == 'L2' ? 'selected' : '' }} >Aras 2</option>
                                  <option value="L3" {{ Auth::user()->level == 'L3' ? 'selected' : '' }} >Aras 3</option>
                                  <option value="L4" {{ Auth::user()->level == 'L4' ? 'selected' : '' }} >Aras 4</option>
                                  <option value="L5" {{ Auth::user()->level == 'L5' ? 'selected' : '' }} >Aras 5</option>
                                  <option value="L6" {{ Auth::user()->level == 'L6' ? 'selected' : '' }} >Aras 6</option>
                                  <option value="L7" {{ Auth::user()->level == 'L7' ? 'selected' : '' }} >Aras 7</option>
                                  <option value="L8" {{ Auth::user()->level == 'L8' ? 'selected' : '' }} >Aras 8</option>
                                  <option value="L9" {{ Auth::user()->level == 'L9' ? 'selected' : '' }} >Aras 9</option>
                                </select>
                                @error('level')
                                <label id="name-error" class="error" for="level">{{$message}}</label>
                                @enderror
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-4">
                            <div class="form-group">
                              <label for="zone" class="col-sm-2 control-label">Zon</label>
                              <div class="col-sm-4">
                                <select name="zone" id="zone" class="form-control zone @error('zone') is-invalid @enderror">
                                  <option value="">Sila Pilih</option>
                                  <option value="A" {{ Auth::user()->zone == 'A' ? 'selected' : '' }} >Zon A</option>
                                  <option value="B" {{ Auth::user()->zone == 'B' ? 'selected' : '' }} >Zon B</option>
                                </select>
                                @error('zone')
                                <label id="name-error" class="error" for="zone">{{$message}}</label>
                                @enderror
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class=" col-sm-6 control-label">No Telefon (Pejabat)</label>
                                    <div class="col-lg-6">
                                        <input class="form-control @error('telephone') is-invalid @enderror" name="telephone" type="text" value="{{ $user->telephone }}">
                                        @error('telephone')
                                        <label id="name-error" class="error" for="telephone">{{$message}}</label>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class=" col-sm-4 control-label">No Telefon (Peribadi)</label>
                                    <div class="col-lg-4">
                                        <input class="form-control @error('handphone') is-invalid @enderror" name="handphone" type="text" value="{{ $user->handphone }}">
                                        @error('handphone')
                                        <label id="name-error" class="error" for="handphone">{{$message}}</label>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class=" col-sm-2 control-label">Emel</label>
                            <div class="col-lg-4">
                                <input class="form-control @error('email') is-invalid @enderror" name="email" type="text" value="{{ $user->email }}">
                                @error('email')
                                <label id="name-error" class="error" for="email">{{$message}}</label>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <input type="submit" class="btn btn-success" name="submit" value="Simpan">
                    </div>
                </section>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <form class="form-horizontal" method="post" action="{{ route('upd_password', Crypt::encrypt($user->id)) }}">
                @csrf
                @method('PUT')
                <section class="panel panel-default">
                    <div class="panel-heading">Kemaskini Katalaluan</div>
                    <div class="panel-body table-responsive">

                        <div class="form-group">
                            <label class=" col-sm-2 control-label">Katalaluan Semasa</label>
                            <div class="col-lg-2">
                                <input class="form-control @error('current_password') is-invalid @enderror" name="current_password" type="password">
                                @error('current_password')
                                <label id="name-error" class="error" for="current_password">{{$message}}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class=" col-sm-2 control-label">Katalaluan Baru</label>
                            <div class="col-lg-2">
                                <input class="form-control @error('new_password') is-invalid @enderror" name="new_password" type="password" value="{{ old('new_password') }}">
                                @error('new_password')
                                <label id="name-error" class="error" for="new_password">{{$message}}</label>
                                @enderror
                                <small class="form-text text-muted">
                                    <strong>Keperluan Kata Laluan:</strong><br>
                                    <span id="req-length" class="text-danger">• Minimum 12 aksara</span><br>
                                    <span id="req-case" class="text-danger">• Mengandungi huruf besar & kecil</span><br>
                                    <span id="req-number" class="text-danger">• Mengandungi nombor</span><br>
                                    <span id="req-symbol" class="text-danger">• Mengandungi simbol</span>
                                </small>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const passwordInput = document.querySelector('input[name="new_password"]');
                                        const reqLength = document.getElementById('req-length');
                                        const reqCase = document.getElementById('req-case');
                                        const reqNumber = document.getElementById('req-number');
                                        const reqSymbol = document.getElementById('req-symbol');

                                        function checkPasswordStrength(password) {
                                            // Check length
                                            if (password.length >= 12) {
                                                reqLength.className = 'text-success';
                                                reqLength.innerHTML = '• Minimum 12 aksara ✓';
                                            } else {
                                                reqLength.className = 'text-danger';
                                                reqLength.innerHTML = '• Minimum 12 aksara';
                                            }

                                            // Check for uppercase and lowercase
                                            const hasUpper = /[A-Z]/.test(password);
                                            const hasLower = /[a-z]/.test(password);
                                            if (hasUpper && hasLower) {
                                                reqCase.className = 'text-success';
                                                reqCase.innerHTML = '• Mengandungi huruf besar & kecil ✓';
                                            } else {
                                                reqCase.className = 'text-danger';
                                                reqCase.innerHTML = '• Mengandungi huruf besar & kecil';
                                            }

                                            // Check for numbers
                                            if (/[0-9]/.test(password)) {
                                                reqNumber.className = 'text-success';
                                                reqNumber.innerHTML = '• Mengandungi nombor ✓';
                                            } else {
                                                reqNumber.className = 'text-danger';
                                                reqNumber.innerHTML = '• Mengandungi nombor';
                                            }

                                            // Check for symbols
                                            if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                                                reqSymbol.className = 'text-success';
                                                reqSymbol.innerHTML = '• Mengandungi simbol ✓';
                                            } else {
                                                reqSymbol.className = 'text-danger';
                                                reqSymbol.innerHTML = '• Mengandungi simbol';
                                            }
                                        }

                                        if (passwordInput) {
                                            passwordInput.addEventListener('input', function() {
                                                checkPasswordStrength(this.value);
                                            });
                                        }
                                    });
                                </script>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class=" col-sm-2 control-label">Ulang Katalaluan Baru</label>
                            <div class="col-lg-2">
                                <input class="form-control @error('repeat_password') is-invalid @enderror" name="repeat_password" type="password">
                                @error('repeat_password')
                                <label id="name-error" class="error" for="repeat_password">{{$message}}</label>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div class="panel-footer">
                        <input type="submit" class="btn btn-success" name="submit" value="Simpan">
                    </div>
                </section>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection