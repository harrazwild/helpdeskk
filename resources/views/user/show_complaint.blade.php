@extends('layout.userlayout')

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
</style>
<script type="text/javascript">
$(document).ready(function(){
  // Sahkan Aduan
  $("#sah").click(function(e){
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

        var id = $("#sah").val();
        var url = '{{ route("verified") }}';

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
              window.location.href = "{{ route('home') }}";
            }
          }

        });
      }
    });

  });

  // Tidak Sahkan Aduan
  $("#xsah").click(function(e){
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

        var id = $("#xsah").val();
        var url = '{{ route("unverified") }}';

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
              window.location.href = "{{ route('home') }}";
            }
          }

        });
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
                    <p class="form-control-static">{{ $complaint->name }}<br>{{ $complaint->ic_number }}</p>
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
                    <p class="form-control-static">{{ App\Helper\Utilities::getSector($complaint->sector_code) }}<br>{{ App\Helper\Utilities::getDepartment($complaint->department_code) }}<br>{{ $lokasi }}<br>{{ $complaint->location }}</p>
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
        
        <div class="panel">
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

      </div>
      <!---------- !END maklumat aduan ---------->
      <!---------- maklumat teknikal ---------->
      @php
      if($complaint->status_id == 1){
          $panel = 'panel-default';
      }elseif($complaint->status_id == 2 || $complaint->status_id == 3 || $complaint->status_id == 9){
          $panel = 'panel-danger';
      }elseif($complaint->status_id == 4 || $complaint->status_id == 5){
          $panel = 'panel-info';
      }elseif($complaint->status_id == 6 || $complaint->status_id == 7){
          $panel = 'panel-primary';       
      }elseif($complaint->status_id == 8){
          $panel = 'panel-success';
      }

      // status aduan
      if($complaint->status_id == 4 || $complaint->status_id == 5){
        $status = 'Pengesahan Pengadu';
      }elseif($complaint->status_id == 8){
        $status = 'Aduan Ditutup';
      }else{
        $status = $complaint->status_desc;
      }
      @endphp

      <div class="col-md-7">
        <div class="panel {{ $panel }}">
          <div class="panel-heading">
            <h4 style="@if($complaint->status_id != 1) color: #fff; @endif">Status : {{ $status }}</h4>
          </div>
          <div class="panel-body">
            <form class="form-horizontal">
            <div class="form-group" id="staff">
              <label class=" col-sm-3 control-label">Pegawai Teknikal</label>
              <div class="col-lg-5">
                <p class="form-control-static" style="white-space: normal;">
                  {!! (!empty($complaint->user_id)) ? App\Helper\Utilities::getStaffName($complaint->user_id) : "-"  !!}    
                </p>
              </div>
            </div>
            @if($complaint->status_id == 9 || isset($complaint->vendor_id))
            <div class="form-group">
              <label class=" col-sm-3 control-label">Pembekal</label>
              <div class="col-lg-5">
                <p class="form-control-static" style="white-space: normal;">
                  {!! (!empty($complaint->vendor_id)) ? App\Helper\Utilities::getVendor($complaint->vendor_id)."<br>" : ""  !!}    
                </p>
              </div>
            </div>
            @endif
            </form>
          </div>
        </div>

        <div class="panel">
          <div class="panel-heading">
              <h4 style="color: #0088CC">Perincian Aduan</h4>
          </div>
          <div class="panel-body">
            <form class="form-horizontal">
            <div class="form-group">
              <label class=" col-sm-3 control-label">Kategori</label>
              <div class="col-lg-3">
                <p class="form-control-static" style="white-space: normal;">
                  {!! (!empty($complaint->category_id)) ? App\Helper\Utilities::getCategory($complaint->category_id) : "-"  !!}    
                </p>
              </div>
            </div>
            @if($complaint->subcategory_id)
            <div class="form-group">
              <label class=" col-sm-3 control-label">Sub Kategori</label>
              <div class="col-lg-4">
                <p class="form-control-static" style="white-space: normal;">
                  {!! (!empty($complaint->subcategory_id)) ? App\Helper\Utilities::getSubCategory($complaint->subcategory_id) : "-"  !!}    
                </p>
              </div>
            </div>
            @endif
            @if($complaint->detail)
            <div class="form-group" id="details">
              <label class=" col-sm-3 control-label">Perincian</label>
              <div class="col-lg-5">
                <p class="form-control-static" style="white-space: normal;">
                  {!! (!empty($complaint->detail)) ? App\Helper\Utilities::getDetail($complaint->detail) : "-"  !!}    
                </p>
              </div>
            </div>
            @endif
            </form>
          </div>
        </div>
        
        <div class="panel" style="margin-bottom: 4px;">
          <div class="panel-heading">
              <h4 style="color: #0088CC">Ulasan Pegawai Teknikal</h4>
          </div>
          <div class="panel-body">
            
            <ul class="task-sum-list">
              @if(!$remarks->isEmpty())
              <!-- looping komen pelaksana dan pentadbir -->
              @foreach($remarks as $data)
              <li class="ulas">
                <div class="row">
                  <div class="col-lg-10"><small>{{ Date('d/m/Y h:i A', strtotime($data->created_at)) }}</small></div>
                </div>
                <div class="row">
                  <div class="col-lg-10">
                    <p style="white-space: pre-wrap;">{{ $data->remarks }}</p>
                  </div>
                </div>
              </li>
              @endforeach
              <!-- end looping komen pelaksana dan pentadbir -->
              @else
              <li class="ulas">
                <div class="row">
                  <div class="col-lg-10">Tiada Maklumat</div>
                </div>
              </li>
              @endif
            </ul>

          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text-left" style="margin-bottom: 20px">
            <a href="{{ route('home') }}" class="btn btn-warning">Kembali</a>
            @if($complaint->status_id == 4 || $complaint->status_id == 5)
            <button class="btn btn-success" id="sah" value="{{ $complaint->id }}"><i class="icon-like"></i> Disahkan Selesai</button>
            <button class="btn btn-danger" id="xsah" value="{{ $complaint->id }}"><i class="icon-dislike"></i> Tidak Disahkan Selesai</button>
            @endif
          </div>
        </div>

      </div>
      <!---------- !END maklumat teknikal ---------->

    </div>

  </div>
</div>
@endsection