@extends('layout.userlayout')

@section('content')
<style>
  .panel
  {
      border: 1px solid #ddd;
  }
  i[class^='icon-'], i[class*=' icon-']
  {
      font-size: 20px;
  }
  h1
  {
      margin-top: 0px;
  }
  .error 
  {
    font-weight: normal;
    color: #ff6c60;
  }
</style>
<script type="text/javascript">
$(document).ready(function(){

  $('#ms_mula, #ms_tamat').timepicker();

  $('.input-daterange').datepicker({
    autoclose: true,
    todayHighlight: true,
    format: 'dd-mm-yyyy'
  });

  // Check for success/error messages and show alerts
  @if(session('success'))
    swal({
      title: "Berjaya!",
      text: '{{ session('success') }}',
      type: "success",
      confirmButtonColor: "#28a745",
      confirmButtonText: "OK"
    });
  @endif

  @if(session('error'))
    swal({
      title: "Gagal!",
      text: '{{ session('error') }}',
      type: "error",
      confirmButtonColor: "#dc3545",
      confirmButtonText: "OK"
    });
  @endif

  // File validation
  $('#attachment').on('change', function() {
    var files = $(this)[0].files;
    var fileTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png', 'application/msword',
     'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel',
      'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
    var maxSize = 20 * 1024 * 1024; // 20MB
    var validFiles = true;
    var errorMessage = '';

    for (var i = 0; i < files.length; i++) {
      var file = files[i];
      
      if (file.size > maxSize) {
        validFiles = false;
        errorMessage = 'File "' + file.name + '" melebihi saiz maksimum 20MB';
        break;
      }
      
      if (!fileTypes.includes(file.type)) {
        validFiles = false;
        errorMessage = 'File "' + file.name + '" bukan jenis PDF, JPG, PNG, Word, atau Excel yang sah';
        break;
      }
    }

    if (!validFiles) {
      swal({
        title: "Ralat!",
        text: errorMessage,
        type: "error",
        confirmButtonColor: "#dc3545",
        confirmButtonText: "OK"
      });
      $(this).val(''); // Clear the file input
    }
  });

  /*... default load is sektor ..*/
    var sektorid = $('.sector').val();

    if(sektorid){
      var url = '{{ route("getDepartments", ":id") }}';
      url = url.replace(':id', sektorid );
      $.ajax({
        type:"GET",
        url: url,
        success:function(res){        
        //console.log(res);
          if(res){
            $(".department").empty();
            $(".department").append('<option value="">Sila Pilih</option>');
            $(".department").append('<option value="NULL">TIDAK BERKENAAN</option>');
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

  /*... bila pilihan sektor ..*/
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
            $(".department").append('<option value="NULL">TIDAK BERKENAAN</option>');
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

  $('#category').on('change', function(){
    var id = $(this).val();

    if(id == 12){
      $('#mesyuarat').show();
      $('#aduan').hide();
    }else{
      $('#mesyuarat').hide();
      $('#aduan').show();
    }
  });
  
});

$(function() {
  $("#device-99").on('click', function () {
    if ($(this).is(":checked")) {
      $("#lain_ict").show();
    } else {
      $("#lain_ict").hide();
    }
  });
});
</script>
<div class="ui-content-body">  
  <div class="ui-container">

  <div class="row">
    <div class="col-sm-12">

      <section class="panel panel-default">
        <div class="panel-heading">Daftar Aduan Baru</div>
        <div class="panel-body">
          <form method="post" action="{{ route('save_complaint') }}" autocomplete="off" id="frmAdd" enctype="multipart/form-data">
            @csrf
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Sektor <span style="color:red"y>*</span></label>
              <div class="col-sm-3">
                <select name="sector" id="sector" class="form-control sector @error('sector') is-invalid @enderror">
                  <option value="">Sila Pilih</option>
                  @foreach($sectors as $row)
                  <option value="{{ $row->sector_code }}" {{ old('sector', Auth::user()->sector_code) == $row->sector_code ? 'selected' : '' }} >{{ $row->sector_desc }}</option>
                  @endforeach
                </select>
                @error('sector')
                <label id="name-error" class="error" for="sector">{{$message}}</label>
                @enderror
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Bahagian <span style="color:red"y>*</span></label>
              <div class="col-sm-5">
                <select name="department" id="department" class="form-control department @error('department') is-invalid @enderror">
                  <option value="">Sila Pilih</option>
                  @foreach($departments as $row)
                  <option value="{{ $row->department_code }}" {{ old('department', Auth::user()->department_code) == $row->department_code ? 'selected' : '' }} >{{ $row->department_desc }}</option>
                  @endforeach
                </select>
                @error('department')
                <label id="name-error" class="error" for="department">{{$message}}</label>
                @enderror
              </div>
            </div>

            <div class="row">
              <div class="col-sm-4">
                <div class="form-group row">
                  <label for="block" class="col-sm-6 col-form-label">Blok <span style="color:red"y>*</span></label>
                  <div class="col-sm-6">
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
              <div class="col-sm-3">
                <div class="form-group row">
                  <label for="level" class="col-sm-4 col-form-label">Aras <span style="color:red"y>*</span></label>
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
                <div class="form-group row">
                  <label for="zone" class="col-sm-3 col-form-label">Zon <span style="color:red"y>*</span></label>
                  <div class="col-sm-6">
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

            <div class="form-group row">
              <label for="location" class="col-sm-2 col-form-label">Lokasi </label>
              <div class="col-sm-6">
                <input type="text" class="form-control location @error('location') is-invalid @enderror" name="location" value="{{ old('location') }}" placeholder="Cth : Bilik Mesyuarat, Bilik Pegawai, Partition No">
                @error('location')
                <label id="name-error" class="error" for="location">{{$message}}</label>
                @enderror
              </div>
            </div>

            <div class="row">
              <div class="col-sm-4">
                <div class="form-group row">
                  <label for="telephone" class="col-sm-6 col-form-label">No. Telefon (Pejabat) <span style="color:red"y>*</span></label>
                  <div class="col-sm-6">
                    <input type="text" maxlength="12" class="form-control telephone @error('telephone') is-invalid @enderror" maxlength="11" id="telephone" name="telephone" value="{{ Auth::user()->telephone }}" onkeypress="return inputLimiter(event,'Numbers')">
                    @error('telephone')
                    <label id="name-error" class="error" for="telephone">{{$message}}</label>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group row">
                  <label for="handphone" class="col-sm-4 col-form-label">No. Telefon (Bimbit) <span style="color:red"y>*</span></label>
                  <div class="col-sm-4">
                    <input type="text" maxlength="12" class="form-control handphone @error('handphone') is-invalid @enderror" maxlength="11" name="handphone" value="{{ Auth::user()->handphone }}" onkeypress="return inputLimiter(event,'Numbers')">
                    @error('handphone')
                    <label id="name-error" class="error" for="handphone">{{$message}}</label>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
            <hr>
            
            <div class="row">
              <div class="col-sm-4">
                <div class="form-group row">
                  <label for="category" class="col-sm-6 col-form-label">Kategori <span style="color:red"y>*</span></label>
                  <div class="col-sm-6">
                    <select class="form-control @error('category') is-invalid @enderror" name="category" id="category">
                      <option value="">Sila Pilih</option>
                      @foreach($categories as $row)
                      <option value="{{ $row->id }}" {{ old('category') == $row->id ? 'selected' : '' }} >{{ $row->category_desc }}</option>
                      @endforeach
                    </select>
                    @error('category')
                    <label id="name-error" class="error" for="category">{{$message}}</label>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
            @php
            if(old('category') != '12'){
              $disp = 'display: block';
            }else{
              $disp = 'display: none';
            }
            @endphp
            <div id="aduan" style="{{ $disp }}">
              <div class="form-group row">
                <label for="remarks" class="col-sm-2 col-form-label">Aduan <span style="color:red"y>*</span></label>
                <div class="col-sm-8">
                  <textarea class="form-control @error('remarks') is-invalid @enderror" name="remarks" id="remarks" cols="30" rows="10">{{ old('remarks') }}</textarea>
                  @error('remarks')
                  <label id="name-error" class="error" for="remarks">{{$message}}</label>
                  @enderror
                </div>
              </div>

              <div class="form-group row">
                <label for="attachment" class="col-sm-2 col-form-label">Lampiran</label>
                <div class="col-sm-8">
                  <input id="attachment" name="attachment[]" type="file" multiple>
                  <small class="form-text text-muted">PDF, JPG, PNG, Word, and Excel files only, under 20MB</small>
                </div>
              </div>
            </div>
            @php
            if(old('category') == '12'){
              $disp = 'display: block';
            }else{
              $disp = 'display: none';
            }

            $n = count($devices);
            $dx = ceil($n/6);
            @endphp
            <div id="mesyuarat" style="{{ $disp }}">
              <div class="form-group row">
                <label for="start" class="col-sm-2 col-form-label">Tarikh <span style="color:red"y>*</span></label>
                <div class="col-sm-6">
                  <div class="input-group date">
                    <div class="input-daterange input-group" id="datepicker">
                      <input type="text" class="form-control @error('start') is-invalid @enderror" name="start" placeholder="dd-mm-yyyy" value="{{ old('start') }}">
                      <span class="input-group-addon"> sehingga </span>
                      <input type="text" class="form-control @error('end') is-invalid @enderror" name="end" placeholder="dd-mm-yyyy" value="{{ old('end') }}">
                    </div>
                    @error('start')
                  <label id="name-error" class="error" for="start">{{$message}}</label>
                  @enderror
                  </div>
                  
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group row">
                    <label for="ms_mula" class="col-sm-6 col-form-label">Masa Mula <span style="color:red"y>*</span></label>
                    <div class="col-sm-4">
                      <div class="input-group bootstrap-timepicker timepicker">
                        <input name="ms_mula" id="ms_mula" type="text" class="form-control @error('ms_mula') is-invalid @enderror" value="{{ old('ms_mula') }}">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-time fa fa-clock-o"></i></span>
                      </div>
                      @error('ms_mula')
                      <label id="name-error" class="error" for="ms_mula">{{$message}}</label>
                      @enderror
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group row">
                    <label for="ms_tamat" class="col-sm-4 col-form-label">Masa Tamat <span style="color:red"y>*</span></label>
                    <div class="col-sm-4">
                      <div class="input-group bootstrap-timepicker timepicker">
                        <input name="ms_tamat" id="ms_tamat" type="text" class="form-control @error('ms_tamat') is-invalid @enderror" value="{{ old('ms_tamat') }}">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-time fa fa-clock-o"></i></span>
                      </div>
                      @error('ms_tamat')
                      <label id="name-error" class="error" for="ms_tamat">{{$message}}</label>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label for="device" class="col-sm-2 col-form-label">Peralatan ICT <span style="color:red"y>*</span></label>
                <div class="col-sm-8">
                  <div class="form-group mb-0 mt-2">
                    @for($x=1;$x<=$dx;$x++)
                    <div class="row">
                      @for($i=($x-1)*6; $i<$x*6 && $i<$n; $i++)
                      <div class="col-md-2">
                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="device-{{ $devices[$i]->id }}" name="device[]" value="{{ $devices[$i]->id }}" >
                          <label for="device-{{ $devices[$i]->id }}" class="custom-control-label">{{ $devices[$i]->device }}</label>
                        </div>
                      </div>
                      @endfor
                    </div>
                    @endfor
                    @error('device')
                    <label id="name-error" class="error" for="device">{{$message}}</label>
                    @enderror
                  </div>
                </div>
              </div>
              <div id="lain_ict" style="display: none;">
                <div class="form-group row">
                  <label for="lain_ict" class="col-sm-2 col-form-label">Lain-lain</label>
                  <div class="col-sm-8">
                    <textarea class="form-control @error('lain_ict') is-invalid @enderror" name="lain_ict" rows="3">{{ old('lain_ict') }}</textarea>
                    @error('lain_ict')
                    <label id="name-error" class="error" for="lain_ict">{{$message}}</label>
                    @enderror
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-2 col-form-label">&nbsp;</label>
              <div class="col-sm-10 text-left">
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                <input type="submit" class="btn btn-success" style="min-width:100px" value="Hantar">
              </div>
            </div>

          </form>
        </div>
      </section>
    </div>
  </div>

  </div>
</div>
@endsection