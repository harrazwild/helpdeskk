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
  $('.modal').on('hidden.bs.modal', function(){
    $(this).find('form')[0].reset();
  });

  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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

          $(".staff").empty();
          $(".staff").append('<option value="">Sila Pilih</option>');
          $.each(res,function(key,value){
          $(".staff").append('<option value="'+value.id+'">'+value.name+'</option>');
          });

        }
      });

    }else{
      
      // clear dropdown sub kategori
      $("#subcategory").empty();
      $("#subcategory").append('<option value="">Sila Pilih</option>');

      // clear dropdown pelaksana
      $(".staff").empty();
      $(".staff").append('<option value="">Sila Pilih</option>');

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
              <h4 style="color: #0088CC">Maklumat Pemohon</h4>
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

        <div class="panel" style="margin-bottom: 4px;">
          <div class="panel-heading">
            <h4 style="color: #0088CC">Keterangan Mesyuarat</h4>
          </div>
          <div class="panel-body">
            <div class="form-group">
              <label class=" col-sm-3 control-label">Tarikh & Masa</label>
              <div class="col-lg-9">
                <p class="form-static">{!! App\Helper\Utilities::meetingTime($complaint->id)  !!}</p>
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
              <label class=" col-sm-3 control-label">Lokasi</label>
              <div class="col-lg-9">
                <p class="form-static"><strong>{{ $complaint->location }}</strong><br>{{ $complaint->sector_desc }}<br>{{ $complaint->department_desc }}<br>{{ $lokasi }}</p>
              </div>
            </div>
            <div class="form-group">
              <label class=" col-sm-3 control-label">Peralatan</label>
              <div class="col-lg-9">
                <p class="form-static">

                  @if($n > 1)
                    @for($i = 0; $i < $n; $i++)
                    <li>{{ $it[$i] }}</li>
                    @endfor
                  @else
                  <li>{{ $it[0] }}</li>
                  @endif

                </p>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12 text-right" style="margin-bottom: 20px">
            <div class="btn-group">
                <!-- <button class="btn btn-default btn-success" id="done" value="{{ $complaint->id }}" @if($complaint->status_id == 4) disabled="" @endif type="button" data-toggle="tooltip" data-placement="bottom" data-original-title="Aduan Selesai"><i class="icon-check"></i></button>
                 <button class="btn btn-default btn-info" id="print" value="{{ $complaint->id }}" type="button" data-toggle="tooltip" data-placement="bottom" data-original-title="Cetak"><i class="icon-printer"></i></button> -->

              @if($userID[0])

                @if($userID[0])
                  <a target="_blank" href="https://wa.me/6{{ App\Helper\Utilities::phonenumber($userID[0]) }}/?text=*Sistem Helpdesk* :%0A{{ $complaint->name }}%0A{{ $complaint->location }}%0A{{ App\Helper\Utilities::getSector($complaint->sector_code) }}%0A{{ App\Helper\Utilities::getDepartment($complaint->department_code) }}%0A%0A{{ $complaint->remarks }}" class="btn btn-success" value="{{ $complaint->id }}" type="button" data-toggle="tooltip" data-placement="top" data-original-title="Whatsapp"><i class="icon-bubble"></i></a>
                @else
                  <a href="#" class="btn btn-success" disabled type="button" data-toggle="tooltip" data-placement="top" data-original-title="Whatsapp"><i class="icon-bubble"></i></a>
                @endif

              @else
                <a href="#" class="btn btn-success" disabled type="button" data-toggle="tooltip" data-placement="top" data-original-title="Whatsapp"><i class="icon-bubble"></i></a>
              @endif
              <button class="btn btn-danger" id="delete" value="{{ $complaint->id }}" type="button" data-toggle="tooltip" data-placement="top" data-original-title="Hapus Aduan"><i class=" icon-trash"></i></button>
            </div>
          </div>
        </div>
      </div>
      <!---------- !END maklumat aduan ---------->
      <!---------- maklumat teknikal ---------->
      @php        
        if($complaint->status_id == 1){
          $panel = 'panel-default';
        }elseif($complaint->status_id == 2){
          $panel = 'panel-danger';
        }elseif($complaint->status_id == 8){
          $panel = 'panel-success';
        }
      @endphp
      <div class="col-md-7">
        <form class="form-horizontal" method="post" action="{{ route('update_meeting') }}">
        @csrf
        <div class="panel {{ $panel }}">
          <div class="panel-heading">
            @if($complaint->status_id == 1)
            <h4 style="@if($complaint->status_id != 1) color: #fff; @endif">Status : Permohonan Baru</h4>
            @elseif($complaint->status_id == 2)
            <h4 style="@if($complaint->status_id != 1) color: #fff; @endif">Status : Dalam Tindakan</h4>
            @elseif($complaint->status_id == 8)
            <h4 style="@if($complaint->status_id != 1) color: #fff; @endif">Status : Selesai</h4>
            @endif
          </div>
          <div class="panel-body">

            <input type="hidden" name="old_taskID1" value="{{ (!empty($taskID[0])) ? $taskID[0] : '' }}">
            <input type="hidden" name="old_taskID2" value="{{ (!empty($taskID[1])) ? $taskID[1] : '' }}">

            <div class="form-group">
              <label class=" col-sm-3 control-label">Pegawai Teknikal 1</label>
              <div class="col-lg-9">
                @if($complaint->status_id == 1 || $complaint->status_id == 2) <!-- jika pentadbir -->
                  <select class="form-control staff" name="staff1">
                    <option value="">Sila Pilih</option>
                    @foreach($staffs as $row)
                    <option value="{{ $row->id }}" @if(old('staff1') == $row->id || (isset($userID[0]) && $userID[0] == $row->id)) selected @endif >{{ $row->name }}</option>
                    @endforeach
                  </select>
                  @error('staff1')
                  <label id="name-error" class="error" for="staff1">{{$message}}</label>
                  @enderror
                @else <!-- jika bukan pentadbir -->
                  <p class="form-control-static" style="white-space: normal;">
                    {!! (!empty($userID[0])) ? App\Helper\Utilities::getStaffName($userID[0]) : "-"  !!}    
                  </p>
                @endif <!-- !end pentadbir -->
              </div>
            </div>

            <div class="form-group">
              <label class=" col-sm-3 control-label">Pegawai Teknikal 2<br>(Jika Berkaitan)</label>
              <div class="col-lg-9">
                @if($complaint->status_id == 1 || $complaint->status_id == 2) <!-- jika pentadbir -->
                  <select class="form-control staff" name="staff2">
                    <option value="">Sila Pilih</option>
                    @foreach($staffs as $row)
                    <option value="{{ $row->id }}" @if(old('staff2') == $row->id || (isset($userID[1]) && $userID[1] == $row->id)) selected @endif >{{ $row->name }}</option>
                    @endforeach
                  </select>
                  @error('staff2')
                  <label id="name-error" class="error" for="staff2">{{$message}}</label>
                  @enderror
                @else <!-- jika bukan pentadbir -->
                  <p class="form-control-static" style="white-space: normal;">
                    {!! (!empty($userID[1])) ? App\Helper\Utilities::getStaffName($userID[1]) : "-"  !!}    
                  </p>
                @endif <!-- !end pentadbir -->
              </div>
            </div>

          </div>
        </div>
        
        <div class="panel">
          <div class="panel-heading">
              <h4 style="color: #0088CC">Perincian Permohonan</h4>
          </div>
          <div class="panel-body">
            <div class="form-group">
              <label class=" col-sm-3 control-label">Kategori</label>
              <div class="col-lg-4">
                <select class="form-control" id="category" name="category">
                  @foreach($categories as $row)
                  <option value="{{ $row->id }}" {{ $complaint->category_id == $row->id || old('category') == $row->id ? 'selected' : '' }} >{{ $row->category_desc }}</option>
                  @endforeach
                </select>
                @error('category')
                <label id="name-error" class="error" for="category">{{$message}}</label>
                @enderror
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
          </div>
        </div>

        <div class="panel" style="margin-bottom: 4px;">
          <div class="panel-heading">
              <h4 style="color: #0088CC">Ulasan</h4>
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
            <a href="{{ route('meetinglist') }}" class="btn btn-warning">Kembali</a>
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