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
        var url = '{{ route("verify") }}';

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
              window.location.href = "{{ route('ontask') }}";
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

        <div class="panel">
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
      </div>
      <!---------- !END maklumat aduan ---------->
      <!---------- maklumat teknikal ---------->
      @php        
        if($complaint->status_id == 2){
          $panel = 'panel-danger';
        }elseif($complaint->status_id == 8){
          $panel = 'panel-success';
        }
      @endphp
      <div class="col-md-7">
        <form class="form-horizontal">
        @csrf
        <div class="panel {{ $panel }}">
          <div class="panel-heading">
            @if($complaint->status_id == 2)  
            <h4 style="color: #fff">Status : Dalam Tindakan</h4>
            @else
            <h4 style="color: #fff">Status : Selesai</h4>
            @endif
          </div>
          <div class="panel-body">
            <div class="form-group" id="staff">
              <label class=" col-sm-3 control-label">Pegawai Teknikal 1</label>
              <div class="col-lg-9">
                  <p class="form-control-static" style="white-space: normal;">
                    {!! (!empty($userID[0])) ? App\Helper\Utilities::getStaffName($userID[0]) : "-"  !!}    
                  </p>
              </div>
            </div>
            <div class="form-group" id="staff">
              <label class=" col-sm-3 control-label">Pegawai Teknikal 2<br>(Jika Berkaitan)</label>
              <div class="col-lg-9">
                  <p class="form-control-static" style="white-space: normal;">
                    {!! (!empty($userID[1])) ? App\Helper\Utilities::getStaffName($userID[1]) : "-"  !!}    
                  </p>
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
              <div class="col-lg-3">
                <p class="form-control-static" style="white-space: normal;">
                  {!! (!empty($complaint->category_id)) ? App\Helper\Utilities::getCategory($complaint->category_id) : "-"  !!}    
                </p>
              </div>
            </div>
            <div class="form-group">
              <label class=" col-sm-3 control-label">Sub Kategori</label>
              <div class="col-lg-4">
                <p class="form-control-static" style="white-space: normal;">
                  {!! (!empty($complaint->subcategory_id)) ? App\Helper\Utilities::getSubCategory($complaint->subcategory_id) : "-"  !!}    
                </p>
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
                  </div>
                </div>
              </li>
              @endforeach
              <!-- end looping komen pelaksana dan pentadbir -->
            </ul>

          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text-left" style="margin-bottom: 20px">
            
            @if($complaint->status_id == 2)
            <a href="{{ route('ontask') }}" class="btn btn-warning">Kembali</a>
            <button class="btn btn-success" id="sah" value="{{ $complaint->id }}"><i class="icon-like"></i> Selesai</button>
            @else
            <a href="{{ route('meetinglist') }}" class="btn btn-warning">Kembali</a>
            @endif
          </div>
        </div>
        </form>

      </div>
      <!---------- !END maklumat teknikal ---------->

    </div>

  </div>
</div>
@endsection