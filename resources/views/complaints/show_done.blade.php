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

  @if(isset($tasks->user_id) && $tasks->user_id != '')
    $('#staff').show();
  @else
    $('#staff').hide();
  @endif

  @if($complaint->officer_id != '')
    $('#officer').show();
  @else
    $('#officer').hide();
  @endif

  @if(isset($complaint->detail))
    $('#details').show();
  @elseif(isset($details))
    $('#details').show();  
  @else
    $('#details').hide();
  @endif

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
      <div class="col-md-7">
        <form class="form-horizontal">
        @csrf
        <div class="panel panel-success">
          <div class="panel-heading">
              <h4 style="color: #fff">Status : Aduan Ditutup</h4>
          </div>
          <div class="panel-body">
            <div class="form-group" id="staff">
              <label class=" col-sm-3 control-label">Pegawai Teknikal</label>
              <div class="col-lg-9">
                  <p class="form-control-static" style="white-space: normal;">
                    {!! (!empty($tasks->user_id)) ? App\Helper\Utilities::getStaffName($tasks->user_id) : "-"  !!}    
                  </p>
              </div>
            </div>
            @if(isset($complaint->officer_id))
            <div class="form-group">
              <label class=" col-sm-3 control-label">Pegawai</label>
              <div class="col-lg-9">
                <p class="form-control-static" style="white-space: normal;">
                  {!! (!empty($complaint->officer_id)) ? App\Helper\Utilities::getStaffName($complaint->officer_id)."<br>" : ""  !!}    
                </p>
              </div>
            </div>
            @endif
            @if($complaint->status_id == 9 || isset($complaint->vendor_id))
            <div class="form-group">
              <label class=" col-sm-3 control-label">Pembekal</label>
              <div class="col-lg-9">
                <p class="form-control-static" style="white-space: normal;">
                  {!! (!empty($complaint->vendor_id)) ? App\Helper\Utilities::getVendor($complaint->vendor_id)."<br>" : ""  !!}    
                </p>
              </div>
            </div>
            @endif
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
                <p class="form-control-static" style="white-space: normal;">
                  {!! (!empty($complaint->subcategory_id)) ? App\Helper\Utilities::getSubCategory($complaint->subcategory_id) : "-"  !!}    
                </p>
              </div>
            </div>
            <div class="form-group" id="details">
              <label class=" col-sm-3 control-label">Perincian</label>
              <div class="col-lg-5">
                <p class="form-control-static" style="white-space: normal;">
                  {!! (!empty($complaint->detail)) ? App\Helper\Utilities::getDetail($complaint->detail) : "-"  !!}    
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
            <a href="{{ route('complaintlist') }}" class="btn btn-warning">Kembali</a>
          </div>
        </div>
        </form>

      </div>
      <!---------- !END maklumat teknikal ---------->

    </div>

  </div>
</div>
@endsection