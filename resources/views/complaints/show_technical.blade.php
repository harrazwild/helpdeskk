@extends('layout.mainlayout')

@section('content')
<style>
small, .small
{
    font-size: 80%;
}
.h4, .h5, .h6, h4, h5, h6
{
    margin-top: 0px;
    margin-bottom: 0px;
}
.Box-wrapper
{
    border: 1px solid #4aa9e9;
    background-color: #fff;
}
.attachment
{
    padding: 20px;
    border: solid 1px #ddd;
}
</style>
<script type="text/javascript">
$(document).ready(function(){

  $('#officer').hide();

  @if(isset($complaint->vendor_id))
    $('#vendor').show();  
  @else
    $('#vendor').hide();
  @endif

  @if(isset($complaint->detail))
    $('#details').show();
  @elseif(isset($details))
    $('#details').show();  
  @else
    $('#details').hide();
  @endif

  $('.modal').on('hidden.bs.modal', function(){
    $(this).find('form')[0].reset();
  });

  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

  // Set sub kategori
  $("#subcategory").change(function(e){

    e.preventDefault();
    var subcategory = $("#subcategory").val();
    var url = '{{ route("getDetails", ":id") }}';
    url = url.replace(':id', subcategory );

    $.ajax({
      type:"GET",
      url: url,
      success:function(res){        
        console.log(res);
        if(res && res.length != 0){
          $(".details").empty();
          $(".details").append('<option value="">Sila Pilih</option>');
          $.each(res,function(key,value){
            $(".details").append('<option value="'+value.id+'">'+value.detail_desc+'</option>');
          });

          $('#details').show();
        }else{
          $('#details').hide();
          $(".details").empty();
        }
      }
    });
 
  });

  // Set status
  $("#status").change(function(e){

    e.preventDefault();
    var status = $("#status").val();
    var vendor = $("#vendor_id").val();
    
    if(status == 3){
      if(vendor != ''){
        $('#vendor').show();
      }else{
        $('#vendor').hide();
        $('select[name="vendor"]').prop('selectedIndex',0);
      }
      $('#officer').show();
    }else if(status == 9){
      $('#vendor').show();
      $('#officer').hide();
      $('select[name="officer"]').prop('selectedIndex',0);
    }else{
      if(vendor != ''){
        $('#vendor').show();
      }else{
        $('#vendor').hide();
        $('select[name="vendor"]').prop('selectedIndex',0);
      }
      $('#officer').hide();
      $('select[name="officer"]').prop('selectedIndex',0);
    }

  });

  // button selesai
    $("#delete").click(function(e){
        
      e.preventDefault();

      swal({
        title: "Adakah Anda Pasti?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "Batal",
        confirmButtonText: "Ya"
      }, function(isConfirm) {
        if (isConfirm) {

          var id = $("#delete").val();
          var url = '{{ route("complaintDelete") }}';

          $.ajax({
            url:url,
            method:'POST',
            data:{
              "_token": "{{ csrf_token() }}",
              "id": id
            },
            success:function(response){
              if(response == 1){
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
                };

                toastr["success"]('Maklumat Berjaya Dihapuskan', 'BERJAYA');
                window.location.href = "{{ route('complaintlist') }}";
              }
            }

          });
        }
      });

    });

    // button kemaskini ulasan
    $('.editRemark').on('click', function(){
      var id = $(this).data('id');

      var url = '{{ route("getRemark", ":id") }}';
      url = url.replace(':id', id );

      $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        success: function(response){
          $('#remarks_id').val(response.id);
          $('#remarks').val(response.remarks);
          
          if(response.mark == 1){
            $('#mark').prop('checked', true);
          }

          $('#editModal').modal('show');      
        },
        error: function (response) {
          console.log(response);
        }
      });

    });

    // button hapus ulasan
    $(".delRemark").click(function(e){
        
      e.preventDefault();

      var id = $(this).data('id');
      var url = '{{ route("delRemark") }}';

      swal({
        title: "Adakah Anda Pasti?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "Batal",
        confirmButtonText: "Ya"
      }, function(isConfirm) {
        if (isConfirm) {

          $.ajax({
            url:url,
            method:'POST',
            data:{
              "_token": "{{ csrf_token() }}",
              "id": id
            },
            success:function(response){
              if(response == 1){
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
                  };

                  toastr["success"]('Maklumat Berjaya Dihapuskan', 'BERJAYA');
                  window.location.reload();
              }
            }

          });
        }
      });

    });

    // Assign Pelaksana
    $("#take").click(function(e){
      e.preventDefault();

      var id = $("#complaint_id").val();
      var staff = '{{ Auth::user()->id }}';
      var url = '{{ route("assigntask") }}';

      $.ajax({
        url:url,
        method:'POST',
        data:{
          "_token": "{{ csrf_token() }}",
          "id": id,
          "staff": staff
        },
        success:function(response){
          if(response == 1){
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
            };

            toastr["success"]('Maklumat Berjaya Dikemaskini', 'BERJAYA');
            window.location.reload();
          }
        }

      });
    });

    // button kemaskini ulasan
    $('.email').on('click', function(){
      var id = $(this).data('id');
      var url = '{{ route("send_emel") }}';
      //url = url.replace(':id', id );

      $.ajax({
        url: url,
        type: 'post',
        data:{
          "_token": "{{ csrf_token() }}",
          "id": id,
        },
        success: function(response){
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

          if(response == 1){
            toastr["success"]('Emel Berjaya Dihantar', 'BERJAYA');
          }else{
            toastr["error"]('Emel Tidak Berjaya Dihantar', 'GAGAL');
          }
          //console.log(response);
        },
        error: function (response) {
          //toastr["error"]('Emel Tidak Berjaya Dihantar', 'GAGAL');
          console.log(response);
        }
      });

    });

});
</script>
<div class="ui-content-body">
  <div class="ui-container">

    <h3>#{{ $complaint->application_no }}</h3>

    <div class="row">
      <!---------- maklumat aduan ---------->
      <div class="col-md-5">
        <div class="panel">
          <div class="panel-heading">
              <h4 style="color: #0088CC">Maklumat Pengadu</h4>
          </div>
          <div class="panel-body">
            <form class="form-horizontal form-variance">
              <div class="form-group">
                <label class=" col-sm-1 control-label"><i class="icon-user"></i></label>
                <div class="col-lg-11">
                  <p class="form-control-static">{{ $complaint->name }}<br>
                    @if($complaint->position_desc != '')
                    {{ $complaint->position_desc }}
                    @endif
                    @if($complaint->grade_desc != '')
                    ({{ $complaint->grade_desc }})
                    @endif
                  </p>
                </div>
              </div>
              @php
                $lokasi = '';
                if($complaint->block != '')
                  $lokasi = "Blok ".$complaint->block;
                if($complaint->level != '')
                  $lokasi .= ", ".$complaint->level;
                if($complaint->zone != '')
                  $lokasi .= ", Zon ".$complaint->zone;
              @endphp
              <div class="form-group">
                <label class=" col-sm-1 control-label"><i class="icon-location-pin"></i></label>
                <div class="col-lg-11">
                    <p class="form-control-static">{{ $complaint->sector_desc }}<br>{{ $complaint->department_desc }}<br>{{ $lokasi }}<br>{{ $complaint->location }}</p>
                </div>
              </div>
              <div class="form-group">
                <label class=" col-sm-1 control-label"><i class="icon-phone"></i></label>
                <div class="col-lg-11">
                    <p class="form-control-static">{{ $complaint->telephone }} (Pejabat)<br>{{ $complaint->handphone }} (Bimbit)</p>
                </div>
              </div>
              <div class="form-group">
                <label class=" col-sm-1 control-label"><i class="icon-envelope"></i></label>
                <div class="col-lg-11">
                    <p class="form-control-static">{{ $complaint->email }}</p>
                </div>
              </div>
            </form>
          </div>
        </div>

        <div class="panel">
          <div class="panel-heading">
              <h4 style="color: #0088CC">Keterangan Aduan</h4>
          </div>
          <div class="panel-body">
            <div class="tsk-title" style="white-space: pre-wrap;"><small>{{ Date('d/m/Y h:i A', strtotime($complaint->date_open)) }}</small><br>{{ $complaint->remarks }}</div>
          </div>
        </div>

        <div class="panel" style="margin-bottom: 4px;">
          <div class="panel-heading">
              <h4 style="color: #0088CC">Lampiran Aduan</h4>
          </div>
          <div class="panel-body table-responsive">
            <table class="table">
              <thead style="background-color: #62549a; color: #ffffff">
                <tr>
                  <td width="5%">Bil</td>
                  <td>Dokumen</td>
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
                <td><a  href="{{ asset('uploads/'.$complaint->application_no.'/'.$data->attachment) }}" target="_blank" style="text-decoration: none;" title="Muatturun"><i class="fa fa-download" style="color: #62549a; font-size: 18px"></i>&nbsp;&nbsp;&nbsp;<small style="color: #62549a">{{ $data->attachment }}</small></a></td>
              </tr>
              @endforeach
              @else
              <tr>
                <td colspan="2" align="center">Tiada Lampiran</td>
              </tr>
              @endif
              </tbody>
            </table>
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-12 text-right" style="margin-bottom: 20px">
            <div class="btn-group">
              <a target="_blank" href="{{ route('logPDF', Crypt::encrypt($complaint->id)) }}" class="btn btn-info" type="button" data-toggle="tooltip" data-placement="top" data-original-title="Cetak Aduan"><i class="icon-printer"></i></a>
            </div>
          </div>
        </div>
        
      </div>
      <!---------- !END maklumat aduan ---------->
      <!---------- maklumat teknikal ---------->
      @php
      if($complaint->status_id == 1){
          $panel = 'panel-default';
      }elseif($complaint->status_id == 2 || $complaint->status_id == 3 || $complaint->status_id == 9 || $complaint->status_id == 11){
          $panel = 'panel-danger';
      }elseif($complaint->status_id == 4 || $complaint->status_id == 5){
          $panel = 'panel-info';
      }elseif($complaint->status_id == 6 || $complaint->status_id == 7){
          $panel = 'panel-primary';       
      }elseif($complaint->status_id == 8){
          $panel = 'panel-success';
      }else{
          $panel = 'panel-default';
      }
      @endphp

      <div class="col-md-7">
        <form class="form-horizontal" method="post" action="{{ route('update_complaint_technical') }}">
        @csrf
        <div class="panel {{ $panel }}">
          <div class="panel-heading">
              <h4 style="@if($complaint->status_id != 1) color: #fff; @endif">Status : {{ $complaint->status_desc }}</h4>
          </div>
          <div class="panel-body">
          
            @if(Auth::user()->role_id != 7 && Auth::user()->role_id != 8)
            <div class="form-group">
              <label class=" col-sm-3 control-label">Pegawai Teknikal</label>
              <div class="col-lg-9">
                <input type="hidden" id="staff_id" value="{{ (!empty($tasks->user_id)) ? $tasks->user_id : ''  }}">
                @if($complaint->status_id == 2 && Auth::user()->role_id == 2) <!-- jika pentadbir -->

                  <select class="form-control" name="staff" id="staff">
                    <option value="">Sila Pilih</option>
                    @foreach($staffs as $row)
                    <option value="{{ $row->id }}" @if(old('staff') == $row->id || (isset($tasks->user_id) && $tasks->user_id == $row->id)) selected @endif >{{ $row->name }}</option>
                    @endforeach
                  </select>
                  @error('staff')
                  <label id="name-error" class="error" for="staff">{{$message}}</label>
                  @enderror

                @else <!-- jika bukan pentadbir -->

                  <p class="form-control-static" style="white-space: normal;">
                    {!! (!empty($tasks->user_id)) ? App\Helper\Utilities::getStaffName($tasks->user_id) : "-"  !!}    
                  </p>

                @endif <!-- !end pentadbir -->
              </div>
            </div>
            @endif
            <div class="form-group">
              <label class=" col-sm-3 control-label">Status Tindakan</label>
              <div class="col-lg-5">
                <select class="form-control" name="status" id="status" required>
                  <option value="">Sila Pilih</option>
                  @if(Auth::user()->role_id == 8)
                    <option value="11">Hantar Untuk Semakan</option>
                  @elseif(Auth::user()->role_id == 7)
                    @if($complaint->status_id == 11)
                      <option value="5">Sahkan Selesai</option>
                      <option value="2">Minta Pembetulan (Kembali ke Vendor)</option>
                    @else
                      <option value="5">Selesai</option>
                    @endif
                  @else
                    <option value="5">Selesai</option>
                    <option value="3">Tindakan Pegawai</option>
                    <option value="9">Tindakan Pembekal</option>
                  @endif
                </select>
              </div>
            </div>
            <div class="form-group" id="officer">
              <label class=" col-sm-3 control-label">Pegawai</label>
              <div class="col-lg-9">
                <select class="form-control" name="officer">
                  <option value="">Sila Pilih</option>
                  @foreach($officers as $row)
                  <option value="{{ $row->id }}" >{{ $row->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-group" id="vendor">
              <label class=" col-sm-3 control-label">Pembekal</label>
              <div class="col-lg-9">
                <input type="hidden" id="vendor_id" value="{{ $complaint->vendor_id }}">
                <select class="form-control" name="vendor">
                  <option value="">Sila Pilih</option>
                  @foreach($vendors as $row)
                  <option value="{{ $row->id }}" @if(isset($complaint->vendor_id) && $complaint->vendor_id == $row->id) selected @endif >{{ $row->vendor_name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

          </div>
        </div>

        <div class="panel">
          <div class="panel-heading">
              <h4 style="color: #0088CC">Perincian Aduan</h4>
          </div>
          <div class="panel-body">
            
            <div class="form-group">
              <label class=" col-sm-3 control-label">Kategori</label>
              <div class="col-lg-3">
                <p class="form-control-static" style="white-space: normal;">
                  {!! (!empty($complaint->category_id)) ? App\Helper\Utilities::getCategory($complaint->category_id) : "-"  !!}    
                </p>
              </div>
            </div>
            <div class="form-group">
              <label class=" col-sm-3 control-label">Sub Kategori</label>
              <div class="col-lg-4">
                <select class="form-control subcategory" id="subcategory" name="subcategory">
                  <option value="">Sila Pilih</option>
                  @foreach($subcategories as $row)
                  <option value="{{ $row->id }}" {{ $complaint->subcategory_id == $row->id || old('subcategory') == $row->id ? 'selected' : '' }} >{{ $row->subcategory_desc }}</option>
                  @endforeach
                </select>
                @error('subcategory')
                <label id="name-error" class="error" for="subcategory">{{$message}}</label>
                @enderror
              </div>
            </div>
            <div class="form-group" id="details">
              <label class=" col-sm-3 control-label">Perincian</label>
              <div class="col-lg-5">
                <select class="form-control select2 details" multiple="" tabindex="-1" aria-hidden="true" name="detail[]">
                  <option value="">Sila Pilih</option>
                  @foreach($details as $row)
                  <option value="{{ $row->id }}" {{ in_array($row->id, $d) ? 'selected' : '' }} >{{ $row->detail_desc }}</option>
                  @endforeach
                </select>
              </div>
            </div>

          </div>
        </div>

        <div class="panel" style="margin-bottom: 4px;">
          <div class="panel-heading">
              <h4 style="color: #0088CC">Ulasan Pegawai Teknikal</h4>
          </div>
          <div class="panel-body">
            
            <ul class="task-sum-list">
              
              <!-- looping komen pelaksana dan pentadbir -->
              @foreach($remarks as $data)
              <li class="ulas">
                <div class="row">
                  <div class="col-lg-10"><b>{{ $data->name }}</b> <small>{{ Date('d/m/Y h:i A', strtotime($data->created_at)) }}</small></div>
                </div>
                <div class="row">
                  <div class="col-lg-10">
                    <p style="white-space: pre-wrap;">{{ $data->remarks }}</p>
                    @if($data->mark == 1)
                    <p><span class="label label-info"><i>Salinan Kepada Pengadu</i></span></p>
                    @endif
                  </div>
                  @if(Auth::user()->id == $data->user_id && $complaint->status_id != 6)
                  <div class="col-lg-2 pull-right">
                    <a href="#" style="text-decoration: none;" class="editRemark" data-id="{{ $data->id }}" title="Kemaskini"><i class="fa fa-pencil" style=" font-size: 16px;"></i></a>
                    <a href="#" style="text-decoration: none;" class="delRemark" data-id="{{ $data->id }}" title="Hapus"><i class="fa fa-trash" style=" font-size: 16px;"></i></a>
                  </div>
                  @endif
                </div>
              </li>
              @endforeach
              <!-- end looping komen pelaksana dan pentadbir -->

              <!-- ulasan -->                
              @if($complaint->status_id != 8)
              <div class="activity-item" >
                <div class="form-group text-right" style="margin-bottom:5px">
                  <label></label>
                  <div class="col-lg-12">
                    <div class="checkbox">
                      <label><input value="1" type="checkbox" name="mark">Salin kepada pengadu</label>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-12">
                        <textarea class="form-control" rows="5" name="remarks"></textarea>
                    </div>
                </div>
              </div>
              @endif
              <!-- end ulasan -->

            </ul>

          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text-left" style="margin-bottom: 20px">
            <input type="hidden" id="complaint_id" name="complaint_id" value="{{ $complaint->id }}">
            <a href="{{ route('complaintlist') }}" class="btn btn-warning">Kembali</a>
            <input type="submit" class="btn btn-success" value="Simpan">
          </div>
        </div>
        </form>

      </div>
      <!---------- !END maklumat teknikal ---------->

    </div>

    <!---------- edit modal form ---------->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="editModal" class="modal fade" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-info">
            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
            <h4 class="modal-title">Kemaskini Ulasan</h4>
          </div>
          <div class="modal-body">

            <form class="form-horizontal" method="post" action="{{ route('updRemark') }}">
              @csrf
              <div class="form-group text-right" style="margin-bottom:5px">
                <label></label>
                <div class="col-lg-12">
                  <div class="checkbox">
                    <label><input value="1" type="checkbox" id="mark" name="mark">Salin kepada pengadu</label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-2 control-label">Ulasan</label>
                <div class="col-lg-10">
                  <textarea class="form-control @error('remarks') is-invalid @enderror" rows="5" name="remarks" id="remarks"></textarea>
                  @error('remarks')
                  <label id="name-error" class="error" for="remarks">{{$message}}</label>
                  @enderror
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-2 control-label"></label>
                <div class="col-lg-10">
                  <input type="hidden" name="remarks_id" id="remarks_id">
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