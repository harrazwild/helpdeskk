@extends('layout.mainlayout')

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
  
  var sect = $('#sector').val();
  var bah = $('#department').val();

  if(sect){
    var url = '{{ route("get_Departments", ":id") }}';
    url = url.replace(':id', sect );
    $.ajax({
      type:"GET",
      url: url,
      success:function(res){        
        if(res){
          $(".department").empty();
          $(".department").append('<option value="">Sila Pilih</option>');
          $.each(res, function(key, value) {
            $(".department").append('<option value="'+ value.deparment_code +'">'+ value.department_desc +'</option>');
          });

          if(bah){
            $('.department option[value="'+bah+'"]').prop('selected', true);
          }
        }else{
          $(".department").empty();
          $(".department").append('<option value="">Sila Pilih</option>');
        }
      }
    });
  }

  $('#btnSemak').click(function(){

    // Department id
    var ic = $('#ic_number').val();

    if(ic != ''){
      var url = '{{ route("getUser", ":id") }}';
      url = url.replace(':id', ic );
      $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        success: function(response){

          if(response == 0){
            // swal({
            //  title: "Maklumat pegawai tidak wujud",
       //        text: "",
       //        type: "error",
            // });
            toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-top-left",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "3000",
                    "hideDuration": "0",
                    "timeOut": "0",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut",
                    "allowHtml": true 
                    }

            toastr["error"]('Maklumat pegawai tidak wujud', 'GAGAL');
            $('#ic_number').val('');
          }else if(response == 1){
            toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-top-left",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "3000",
                    "hideDuration": "0",
                    "timeOut": "0",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut",
                    "allowHtml": true 
                    }

            toastr["error"]('Pegawai Bukan Di Ibu Pejabat JAN', 'GAGAL');
            $('#ic_number').val('');  
          }else{
            $('#utama').hide();
            $('#aduan').show();

            $(".department").empty();
            $(".department").append('<option value="">Sila Pilih</option>');
            $.each(response.list_bhgn, function(key, value) {
              $(".department").append('<option value="'+ value.department_code +'">'+ value.department_desc +'</option>');
            });

            $('.user_id').val(response.id);
            $('.name').val(response.nama);
            $('.ic_no').val(response.ic);
            $('.gred').val(response.gred);
            $('.jawatan').val(response.jawatan);
            $('.sector option[value="'+response.sektor+'"]').prop('selected', true);
            $('.department option[value="'+response.bahagian+'"]').prop('selected', true);  
            $('.telephone').val(response.telefon);  
            $('.handphone').val(response.tel_bimbit);  
            $('.email').val(response.email);  
          } 
        },
        error: function (response) {
          console.log(response);
        }
      });
    }else{
      // swal({
      //  title: "Sila Masukkan No Kad Pengenalan",
 //        text: "",
 //        type: "error",
      // });
      toastr.options = {
              "closeButton": true,
              "debug": false,
              "newestOnTop": false,
              "progressBar": false,
              "positionClass": "toast-top-left",
              "preventDuplicates": false,
              "onclick": null,
              "showDuration": "3000",
              "hideDuration": "0",
              "timeOut": "0",
              "extendedTimeOut": "1000",
              "showEasing": "swing",
              "hideEasing": "linear",
              "showMethod": "fadeIn",
              "hideMethod": "fadeOut",
              "allowHtml": true 
              }

      toastr["error"]('Sila Masukkan No Kad Pengenalan', 'GAGAL');
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

  // Set semula kategori
  $("#category").change(function(e){
      
    e.preventDefault();
    var category = $("#category").val();
    
    if(category){
      var url_1 = '{{ route("getSubCat", ":id") }}';
      url_1 = url_1.replace(':id', category);

      // dapatkan list utk dropdown sub kategori
      $.ajax({
        type:"GET",
        url: url_1,
        success:function(res){        

          $("#subcategory").empty();
          $("#subcategory").append('<option value="">Sila Pilih</option>');
          $.each(res,function(key,value){
          $("#subcategory").append('<option value="'+value.id+'">'+value.subcategory_desc+'</option>');
          });

        }
      });

      var url_2 = '{{ route("getStaff", ":id") }}';
      url_2 = url_2.replace(':id', category);

      $.ajax({
        type:"GET",
        url: url_2,
        success:function(res){        

          $("#staff").empty();
          $("#staff").append('<option value="">Sila Pilih</option>');
          $.each(res,function(key,value){
          $("#staff").append('<option value="'+value.id+'">'+value.name+'</option>');
          });

        }
      });

    }else{
      
      // clear dropdown sub kategori
      $("#subcategory").empty();
      $("#subcategory").append('<option value="">Sila Pilih</option>');

      // clear dropdown pelaksana
      $("#staff").empty();
      $("#staff").append('<option value="">Sila Pilih</option>');

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
          <form method="post" action="{{ route('save_newcomplaint') }}" autocomplete="off" id="frmAdd" enctype="multipart/form-data">
            @csrf
            <div class="form-group row">
              <label for="ic_number" class="col-sm-2 col-form-label">No Kad Pengenalan</label>
              <div class="col-sm-3">
                <input type="text" name="ic_number" id="ic_number" class="form-control" value="{{ old('ic_no') }}" placeholder="Masukkan No Pengenalan Pengadu" onkeypress="return inputLimiter(event,'Numbers')">                         
              </div>
              <div class="col-sm-2">
                <input type="button" id="btnSemak" class="btn btn-success" style="min-width:100px" value="Cari">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Nama <span style="color:red"y>*</span></label>
              <div class="col-sm-6">
                <input type="text" class="form-control name" disabled="" value="{{ old('name') }}">
                <input type="hidden" name="name" class="name" >
                <input type="hidden" name="grade_id" class="gred" >
                <input type="hidden" name="position_id" class="jawatan" >
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Sektor <span style="color:red"y>*</span></label>
              <div class="col-sm-4">
                <select name="sector" id="sector" class="form-control sector @error('sector') is-invalid @enderror">
                  <option value="">Sila Pilih</option>
                  @foreach($sectors as $row)
                  <option value="{{ $row->sector_code }}" {{ old('sector') == $row->sector_code ? 'selected' : '' }} >{{ $row->sector_desc }}</option>
                  @endforeach
                </select>
                @error('sector')
                <label id="name-error" class="error" for="sector">{{$message}}</label>
                @enderror
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Bahagian <span style="color:red"y>*</span></label>
              <div class="col-sm-8">
                <select name="department" id="department" class="form-control department @error('department') is-invalid @enderror">
                  <option value="">Sila Pilih Sektor</option>
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
                      <option value="F2" {{ old('block') == 'F2' ? 'selected' : '' }} >F2</option>
                      <option value="F3" {{ old('block') == 'F3' ? 'selected' : '' }} >F3</option>
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
              <div class="col-sm-4">
                <div class="form-group row">
                  <label for="zone" class="col-sm-3 col-form-label">Zon <span style="color:red"y>*</span></label>
                  <div class="col-sm-6">
                    <select name="zone" id="zone" class="form-control zone @error('zone') is-invalid @enderror">
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

            <div class="form-group row">
              <label for="location" class="col-sm-2 col-form-label">Lokasi </label>
              <div class="col-sm-6">
                <input type="text" class="form-control location @error('location') is-invalid @enderror" name="location" value="{{ old('location') }}">
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
                    <input type="text" maxlength="12" class="form-control telephone @error('telephone') is-invalid @enderror" id="telephone" name="telephone" value="{{ old('telephone') }}" onkeypress="return inputLimiter(event,'Numbers')">
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
                    <input type="text" maxlength="12" class="form-control handphone @error('handphone') is-invalid @enderror" name="handphone" value="{{ old('handphone') }}" onkeypress="return inputLimiter(event,'Numbers')">
                    @error('handphone')
                    <label id="name-error" class="error" for="handphone">{{$message}}</label>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <label for="email" class="col-sm-2 col-form-label">Emel <span style="color:red"y>*</span></label>
              <div class="col-sm-4">
                <input type="text" class="form-control email @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
                @error('email')
                <label id="name-error" class="error" for="email">{{$message}}</label>
                @enderror
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
              <div class="col-sm-4">
                <div class="form-group row">
                  <label for="subcategory" class="col-sm-4 col-form-label">Sub Kategori </label>
                  <div class="col-sm-6">
                    <select class="form-control @error('subcategory') is-invalid @enderror" name="subcategory" id="subcategory">
                      <option value="">Sila Pilih</option>
                    </select>
                    @error('subcategory')
                    <label id="name-error" class="error" for="subcategory">{{$message}}</label>
                    @enderror
                  </div>
                </div>
              </div>
              <!-- <div class="col-sm-4">
                <div class="form-group row" style="display: none;" id="details">
                  <label for="details" class="col-sm-6 col-form-label">Perincian <span style="color:red"y>*</span></label>
                  <div class="col-sm-6">
                    <select class="form-control @error('details') is-invalid @enderror" name="details" id="details">
                    </select>
                    @error('details')
                    <label id="name-error" class="error" for="details">{{$message}}</label>
                    @enderror
                  </div>
                </div>
              </div> -->
            </div>

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
              <label for="remarks" class="col-sm-2 col-form-label">Lampiran</label>
              <div class="col-sm-8">
                <input id="attachment" name="attachment[]" type="file" multiple>
                <small class="form-text text-muted">PDF and JPG files only, under 20MB</small>
              </div>
            </div>

            @if(Auth::user()->role_id != 3)
            <div class="form-group row">
              <label for="staff" class="col-sm-2 col-form-label">Pelaksana</label>
              <div class="col-sm-4">
                <select class="form-control" name="staff" id="staff">
                  <option value="">Sila Pilih</option>
                </select>  
              </div>
            </div>
            @endif
            
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">&nbsp;</label>
              <div class="col-sm-10 text-left">
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