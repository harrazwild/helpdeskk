@extends('layout.mainlayout')

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

    if(department == '2.1.3'){
        $('#sectionDIV').show();
    }else{
        $('#sectionDIV').hide();
    }

    if($('#role_id').val() == 7 || $('#role_id').val() == 8){
        $('#subcategoriesDIV').show();
        $('#subcategories').select2({ width: '100%' });
    }else{
        $('#subcategoriesDIV').hide();
    }

    if($('#role_id').val() == 8){
        $('#gredJawatanDIV').hide();
    }else{
        $('#gredJawatanDIV').show();
    }

    $('#role_id').on('change',function(e) {
        var id = $(this).val();
        if(id == 7 || id == 8){
          $('#subcategoriesDIV').show();
          $('#subcategories').select2({ width: '100%' });
        }else{
          $('#subcategoriesDIV').hide();
        }

        if(id == 8){
            $('#gredJawatanDIV').hide();
        }else{
            $('#gredJawatanDIV').show();
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

    $('.department').on('change',function(e) {
        var id = $(this).val();
        if(id == '2.1.3'){
          $('#sectionDIV').show();
        }else{
          $('#sectionDIV').hide();
        }
    });

});
</script>
<div class="ui-content-body">  
    <div class="ui-container">

        <div class="row">
            <div class="col-sm-12">
                <form class="form-horizontal" method="post" action="{{ route('save_user') }}">
                @csrf
                <section class="panel panel-default">
                    <div class="panel-heading">Tambah Pengguna</div>
                    <div class="panel-body table-responsive">

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class=" col-sm-4 control-label">Peranan</label>
                                    <div class="col-lg-6">
                                        <select name="role_id" id="role_id" class="form-control @error('role_id') is-invalid @enderror">
                                            <option value="">Sila Pilih</option>
                                            @foreach($roles as $row)
                                            <option value="{{ $row->id }}" {{ old('role_id') == $row->id ? 'selected' : '' }} >{{ $row->role_desc }}</option>
                                            @endforeach
                                        </select>
                                        @error('role_id')
                                        <label id="name-error" class="error" for="role_id">{{$message}}</label>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class=" col-sm-4 control-label">No Kad Pengenalan</label>
                                    <div class="col-lg-6">
                                        <input class="form-control @error('ic_number') is-invalid @enderror" name="ic_number" type="text" value="{{ old('ic_number') }}">
                                        @error('ic_number')
                                        <label id="name-error" class="error" for="ic_number">{{$message}}</label>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class=" col-sm-2 control-label">Nama</label>
                            <div class="col-lg-6">
                                <input class="form-control @error('name') is-invalid @enderror" name="name" type="text" value="{{ old('name') }}">
                                @error('name')
                                <label id="name-error" class="error" for="name">{{$message}}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="row" id="gredJawatanDIV">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class=" col-sm-4 control-label">Gred</label>
                                    <div class="col-lg-6">
                                        <select class="form-control select2 @error('grade_id') is-invalid @enderror" name="grade_id" id="grade_id">
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
                                <div class="form-group">
                                    <label class=" col-sm-4 control-label">Jawatan</label>
                                    <div class="col-lg-6">
                                        <select class="form-control select2 @error('position_id') is-invalid @enderror" name="position_id" id="position_id">
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
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class=" col-sm-4 control-label">Sektor</label>
                                    <div class="col-lg-8">
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
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class=" col-sm-2 control-label">Bahagian</label>
                                    <div class="col-lg-8">
                                        <select name="department_id" id="department_id" class="form-control select2 department @error('department_id') is-invalid @enderror">
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
                            </div>
                        </div>
                        <div class="form-group" id="sectionDIV">
                            <label class=" col-sm-2 control-label">Seksyen</label>
                            <div class="col-lg-4">
                                <select name="section_id" id="section_id" class="form-control section @error('section_id') is-invalid @enderror">
                                    <option value="">Sila Pilih</option>
                                    @foreach($sections as $row)
                                    <option value="{{ $row->id }}" {{ old('section_id') == $row->id ? 'selected' : '' }} >{{ $row->section_desc }}</option>
                                    @endforeach
                                </select>
                                @error('section_id')
                                <label id="name-error" class="error" for="section_id">{{$message}}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group" id="subcategoriesDIV" style="display:none;">
                            <label class=" col-sm-2 control-label">Kategori Aplikasi</label>
                            <div class="col-lg-10">
                                <select name="subcategories[]" id="subcategories" class="form-control select2 @error('subcategories') is-invalid @enderror" multiple>
                                    @foreach($subcategories as $row)
                                    <option value="{{ $row->id }}" {{ (is_array(old('subcategories')) && in_array($row->id, old('subcategories'))) ? 'selected' : '' }} >{{ $row->subcategory_desc }}</option>
                                    @endforeach
                                </select>
                                <span class="help-block">Pilih aplikasi yang dijaga. Boleh pilih lebih dari satu (Khusus untuk Peranan Pegawai Aplikasi & Vendor).</span>
                                @error('subcategories')
                                <label id="name-error" class="error" for="subcategories">{{$message}}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class=" col-sm-6 control-label">Blok</label>
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
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class=" col-sm-4 control-label">Aras</label>
                                    <div class="col-lg-6">
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
                                <div class="form-group">
                                    <label class=" col-sm-4 control-label">Zon</label>
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
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class=" col-sm-6 control-label">No Telefon (Pejabat)</label>
                                    <div class="col-lg-6">
                                        <input class="form-control @error('telephone') is-invalid @enderror" name="telephone" type="text" value="{{ old('telephone') }}">
                                        @error('telephone')
                                        <label id="name-error" class="error" for="telephone">{{$message}}</label>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class=" col-sm-6 control-label">No Telefon (Peribadi)</label>
                                    <div class="col-lg-6">
                                        <input class="form-control @error('handphone') is-invalid @enderror" name="handphone" type="text" value="{{ old('handphone') }}">
                                        @error('handphone')
                                        <label id="name-error" class="error" for="handphone">{{$message}}</label>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class=" col-sm-2 control-label">Emel</label>
                                    <div class="col-lg-10">
                                        <input class="form-control @error('email') is-invalid @enderror" name="email" type="text" value="{{ old('email') }}">
                                        @error('email')
                                        <label id="name-error" class="error" for="email">{{$message}}</label>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="panel-footer">
                        <a href="{{ route('user') }}" class="btn btn-info">Kembali</a>
                        <input type="submit" class="btn btn-success" name="submit" value="Simpan">
                    </div>
                </section>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection