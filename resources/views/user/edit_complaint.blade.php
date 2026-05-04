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
</style>
<script type="text/javascript">
$(document).ready(function(){
  
  $('.modal').on('hidden.bs.modal', function(){
      $(this).find('form')[0].reset();
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

  // File validation for modal
  $('#attachment').on('change', function() {
    var file = $(this)[0].files[0];
    var fileTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png', 'application/msword',
     'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel',
      'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
    var maxSize = 20 * 1024 * 1024; // 20MB
    var validFile = true;
    var errorMessage = '';

    if (file) {
      if (file.size > maxSize) {
        validFile = false;
        errorMessage = 'File "' + file.name + '" melebihi saiz maksimum 20MB';
      }
      
      if (!fileTypes.includes(file.type)) {
        validFile = false;
        errorMessage = 'File "' + file.name + '" bukan jenis PDF, JPG, PNG, Word, atau Excel yang sah';
      }
    }

    if (!validFile) {
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

      <section class="panel panel-default">
        <div class="panel-heading">Daftar Aduan Baru</div>
        <div class="panel-body">
          <form method="post" action="{{ route('upd_complaint') }}" autocomplete="off" id="frmAdd">
            @csrf
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Sektor <span style="color:red"y>*</span></label>
              <div class="col-sm-3">
                <select name="sector" id="sector" class="form-control sector @error('sector') is-invalid @enderror">
                  <option value="">Sila Pilih</option>
                  @foreach($sectors as $row)
                  <option value="{{ $row->sector_code }}" {{ $complaint->sector_code == $row->sector_code ? 'selected' : '' }} >{{ $row->sector_desc }}</option>
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
                  <option value="{{ $row->department_code }}" {{ $complaint->department_code == $row->department_code ? 'selected' : '' }} >{{ $row->department_desc }}</option>
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
                      <option value="F2" {{ $complaint->block == 'F2' ? 'selected' : '' }} >F2</option>
                      <option value="F3" {{ $complaint->block == 'F3' ? 'selected' : '' }} >F3</option>
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
                      <option value="L1" {{ $complaint->level == 'L1' ? 'selected' : '' }} >Aras 1</option>
                      <option value="L2" {{ $complaint->level == 'L2' ? 'selected' : '' }} >Aras 2</option>
                      <option value="L3" {{ $complaint->level == 'L3' ? 'selected' : '' }} >Aras 3</option>
                      <option value="L4" {{ $complaint->level == 'L4' ? 'selected' : '' }} >Aras 4</option>
                      <option value="L5" {{ $complaint->level == 'L5' ? 'selected' : '' }} >Aras 5</option>
                      <option value="L6" {{ $complaint->level == 'L6' ? 'selected' : '' }} >Aras 6</option>
                      <option value="L7" {{ $complaint->level == 'L7' ? 'selected' : '' }} >Aras 7</option>
                      <option value="L8" {{ $complaint->level == 'L8' ? 'selected' : '' }} >Aras 8</option>
                      <option value="L9" {{ $complaint->level == 'L9' ? 'selected' : '' }} >Aras 9</option>
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
                      <option value="A" {{ $complaint->zone == 'A' ? 'selected' : '' }} >Zon A</option>
                      <option value="B" {{ $complaint->zone == 'B' ? 'selected' : '' }} >Zon B</option>
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
                <input type="text" class="form-control location @error('location') is-invalid @enderror" name="location" value="{{ $complaint->location }}" placeholder="Cth : Bilik Mesyuarat, Bilik Pegawai, Partition No">
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
                    <input type="text" maxlength="12" class="form-control telephone @error('telephone') is-invalid @enderror" maxlength="11" id="telephone" name="telephone" value="{{ $complaint->telephone }}" onkeypress="return inputLimiter(event,'Numbers')">
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
                    <input type="text" maxlength="12" class="form-control handphone @error('handphone') is-invalid @enderror" maxlength="11" name="handphone" value="{{ $complaint->handphone }}" onkeypress="return inputLimiter(event,'Numbers')">
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
                      <option value="{{ $row->id }}" {{ $complaint->category_id == $row->id ? 'selected' : '' }} >{{ $row->category_desc }}</option>
                      @endforeach
                    </select>
                    @error('category')
                    <label id="name-error" class="error" for="category">{{$message}}</label>
                    @enderror
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group row">
              <label for="remarks" class="col-sm-2 col-form-label">Aduan <span style="color:red"y>*</span></label>
              <div class="col-sm-8">
                <textarea class="form-control @error('remarks') is-invalid @enderror" name="remarks" id="remarks" cols="30" rows="10">{{ $complaint->remarks }}</textarea>
                @error('remarks')
                <label id="name-error" class="error" for="remarks">{{$message}}</label>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label for="remarks" class="col-sm-2 col-form-label">Lampiran</label>
              <div class="col-sm-10">
                <div class="text-right" style="margin-bottom: 5px;">
                  <a href="#addModal" data-toggle="modal" class="btn btn-sm btn-info">Tambah Lampiran</a>
                </div>
                <table class="table">
                  <thead style="background-color: #62549a; color: #ffffff">
                    <tr>
                      <td width="5%">Bil</td>
                      <td>Dokumen</td>
                      <td width="5%">Aktiviti</td>
                    </tr>
                  </thead>
                  <tbody>
                  @php
                  $bil = 1;
                  @endphp  
                  @if(count($attach) > 0)
                  @foreach($attach as $data)
                  <tr>
                    <td align="center">{{ $bil++ }}.</td>
                    <td><a class="attachment" href="{{ asset('uploads/'.$complaint->application_no.'/'.$data->attachment) }}" target="_blank" style="text-decoration: none;" title="Muatturun"><i class="fa fa-download" style="color: #62549a; font-size: 18px"></i>&nbsp;&nbsp;&nbsp;<small style="color: #62549a">{{ $data->attachment }}</small></a></td>
                    <td align="center"><a href="{{ route('del_file', Crypt::encrypt($data->id.'|'.$complaint->id)) }}" style="text-decoration: none;" title="Hapus"><i class="icon-trash"></i></a></td>
                  </tr>
                  @endforeach
                  @else
                  <tr>
                    <td colspan="3" align="center">Tiada Lampiran</td>
                  </tr>
                  @endif
                  </tbody>
                </table>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-2 col-form-label">&nbsp;</label>
              <div class="col-sm-10 text-left">
                <input type="hidden" name="id" value="{{ $complaint->id }}">
                <a href="{{ route('home') }}" class="btn btn-warning">Kembali</a>
                <input type="submit" class="btn btn-success" style="min-width:100px" value="Hantar">
              </div>
            </div>

          </form>
        </div>
      </section>
    </div>
  </div>

  <!---------- edit modal form ---------->
  <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="addModal" class="modal fade" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
          <h4 class="modal-title">Tambah Lampiran</h4>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" method="post" action="{{ route('add_file') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group row">
              <label class="col-lg-4 control-label">Dokumen</label>
              <div class="col-lg-8">
                <input id="attachment" name="attachment" type="file">
                <small class="form-text text-muted">PDF, JPG, PNG, Word, and Excel files only, under 20MB</small>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-4 control-label"></label>
              <div class="col-lg-8">
                <input type="hidden" name="id" id="id" value="{{ $complaint->id }}">
                <input type="submit" class="btn btn-success" name="submit" value="Simpan">
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!---------- !end edit modal form ---------->

  </div>
</div>
@endsection