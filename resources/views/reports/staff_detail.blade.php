
@extends('layout.mainlayout')

@section('content')
<style>
tbody.collapse.in 
{
  display: table-row-group;
}
</style>
<script type="text/javascript">
  $(document).ready(function() {
    
    // Then attach the picker to the element you want to trigger it
    var oDate = $('input[name="daterange"]').daterangepicker({
      startDate: '{{ $sDate }}', 
      endDate: '{{ $eDate }}',
      locale: {
                cancelLabel: 'Clear',
                format: 'DD-MM-YYYY'
              }
    });

    $("#section").change(function(e){
      
      e.preventDefault();
      var section = $("#section").val();
      
      if(section){

        var url = '{{ route("get_Staff", ":id") }}';
        url = url.replace(':id', section);

        $.ajax({
          type:"GET",
          url: url,
          success:function(res){        

            $("#staff").empty();
            $("#staff").append('<option value="">Sila Pilih</option>');
            $.each(res,function(key,value){
            $("#staff").append('<option value="'+value.id+'">'+value.name+'</option>');
            });

          }
        });

      }else{
        
        // clear dropdown pelaksana
        $("#staff").empty();
        $("#staff").append('<option value="">Sila Pilih</option>');

      }

    });

    $('.ulasan').on('click', function () {
        var id = $(this).attr("data-id");
        var url = '{{ route("getUlasan", ":id") }}';
        url = url.replace(':id', id );        

        $.ajax({
            url: url,
            type: 'get',
            dataType: 'html',
            success: function (data) {
                $('#showresults').html(data);
            },
            error: function (xhr, status) {
                alert("Sorry, there was a problem!");
            }
        });
    });

  });
</script>
@php
if(isset($s)){
  $s = $s;
}else{
  $s = 0;
}

if(isset($st)){
  $st = $st;
}else{
  $st = 0;
}

if(isset($d)){
  $d = $d;
}else{
  $d = 0;
}
@endphp 
<div class="ui-content-body">
  <div class="ui-container">

    <div class="row">
      <div class="col-sm-12">
        <section class="panel panel-default">
          <div class="panel-heading">Laporan Perincian Pegawai Teknikal</div>
          <div class="panel-body table-responsive">
              
            <form class="form-horizontal" method="get">
            @csrf

              <div class="form-group">
                  <label for="sector" class="col-sm-2 control-label">Tempoh</label>
                  <div class="col-lg-3">
                    <input class="form-control" type="text" name="daterange">
                  </div>
              </div>
              <div class="form-group">
                  <label for="sector" class="col-sm-2 control-label">Seksyen</label>
                  <div class="col-lg-3">
                    <select class="form-control" id="section" name="section">
                      <option value="" >Sila Pilih</option>
                      <option value="1" @if(!empty($sc) && $sc == 1) selected @endif >Aplikasi</option>
                      <option value="2" @if(!empty($sc) && $sc == 2) selected @endif >Operasi</option>
                      <option value="3" @if(!empty($sc) && $sc == 3) selected @endif >Pentadbiran</option>
                    </select>
                  </div>
              </div>
              <div class="form-group">
                  <label for="sector" class="col-sm-2 control-label">Pegawai Teknikal</label>
                  <div class="col-lg-3">
                    <select class="form-control" id="staff" name="staff">
                      <option value="" >Sila Pilih</option>
                      @foreach($staff as $row)
                      <option value="{{ $row->id }}" @if(!empty($s) && $s == $row->id) selected @endif >{{ $row->name }}</option>
                      @endforeach
                    </select>
                  </div>
              </div>
              <div class="form-group">
                  <label for="sector" class="col-sm-2 control-label">Status Aduan</label>
                  <div class="col-lg-3">
                    <select class="form-control" id="status" name="status">
                      <option value="" >Sila Pilih</option>
                      @foreach($status as $row)
                      <option value="{{ $row->id }}" @if(!empty($st) && $st == $row->id) selected @endif >{{ $row->status_desc }}</option>
                      @endforeach
                    </select>
                  </div>
              </div>
              <div class="form-group">
                  <label for="sector" class="col-sm-2 control-label">Tempoh KPI</label>
                  <div class="col-lg-3">
                    <select class="form-control" id="kpi" name="kpi">
                      <option value="">Keseluruhan</option>
                      <option value="2" @if(!empty($d) && $d == 2) selected @endif >Lebih 3 Hari</option>
                      <option value="3" @if(!empty($d) && $d == 3) selected @endif >Lebih 5 Hari</option>
                    </select>
                  </div>
              </div>

              <div class="form-group row">
                  <label class="col-sm-2 col-form-label">&nbsp;</label>
                  <div class="col-sm-10 text-left">
                    <input type="submit" class="btn btn-success" style="min-width:100px" value="Hantar">
                    <a href="{{ route('staff_detail') }}" class="btn btn-warning">Semula</a>
                  </div>
              </div>
        
            </form>

          </div>
        </section>
      </div>
    </div>

    @if($s)
    <div class="row">
      <div class="col-md-12">
        <div class="text-right" style="margin-bottom: 5px; margin-right: 10px">
          <div class="btn-group">
            <button data-toggle="dropdown" class="btn btn-info dropdown-toggle" type="button" aria-expanded="false">Cetak <span class="caret"></span></button>
            <ul role="menu" class="dropdown-menu">
              <li><a href="{{ route('staffDetailPDF', ['sDate'=>$sDate, 'eDate'=>$eDate, 'staff'=>$s, 'status'=>$st, 'kpi'=>$d]) }}" target="_blank">PDF</a></li>
              <li><a href="{{ route('staffDetailExcel', ['sDate'=>$sDate, 'eDate'=>$eDate, 'staff'=>$s, 'status'=>$st, 'kpi'=>$d]) }}" target="_blank">Excel</a></li>
            </ul>
          </div>
        </div>
        <section class="panel panel-default">
          <div class="panel-body table-responsive">
            <form class="form-horizontal">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                      <label for="sector" class="col-sm-2 control-label">Nama</label>
                      <div class="col-lg-10">
                        <p class="form-control-static">{{ App\Helper\Utilities::getStaffName($s) }}</p>
                      </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                      <label for="sector" class="col-sm-2 control-label">Jawatan</label>
                      <div class="col-lg-10">
                        <p class="form-control-static">{{ App\Helper\Utilities::getStaffPosition($s) }}</p>
                      </div>
                  </div>
                </div>
              </div>  
            </form>
            <table class="table table-bordered table-striped report_1">
              <thead>
                <tr class="bg-primary">
                  <th width="2%" rowspan="2">Bil</th>
                  <th width="8%" rowspan="2">No Aduan</th>
                  <th rowspan="2">Nama Pengadu</th>
                  <th rowspan="2">Sektor/Bahagian</th>
                  <th rowspan="2">Status Aduan</th>
                  <th width="8%" rowspan="2">Tarikh Aduan</th>
                  <th colspan="2" align="center">Aduan Selesai</th>
                  <th colspan="2" align="center">Aduan Ditutup</th>
                  <th width="5%" rowspan="2">Ulasan</th>
                </tr>
                <tr class="bg-primary">
                  <th width="8%">Tarikh</th>
                  <th width="8%">Tempoh</th>
                  <th width="8%">Tarikh</th>
                  <th width="8%">Tempoh</th>
                </tr>
              </thead>
              <tbody>
                @php  
                $bil = 1;
                @endphp
                @if(!$complaints->isEmpty())
                @foreach($complaints as $row)
                <tr>
                  <td align="right">{{ $bil++ }}.</td>
                  <td>#{{ $row->application_no }}</td>
                  <td>{{ $row->name }}</td>
                  <td>{!! App\Helper\Utilities::getSector($row->sector_code).'<br>'.App\Helper\Utilities::getDepartment($row->department_code) !!}</td>
                  <td>{{ $row->status_desc }}</td>
                  <td align="center">{{ date('d-m-Y', strtotime($row->date_open)) }}</td>
                  <td align="center">{{ (!empty($row->date_job_done)) ? date('d-m-Y', strtotime($row->date_job_done)) : "-"  }}</td>
                  <td align="center">{{ $row->tempoh_selesai }} hari</td>
                  <td align="center">{{ (!empty($row->date_close)) ? date('d-m-Y', strtotime($row->date_close)) : "-"  }}</td>
                  <td align="center">{{ $row->tempoh_ditutup }} hari</td>
                  <td align="center"><a href="#" data-id="{{ $row->id }}" class="ulasan" data-toggle="modal" data-target="#myModal" style="text-decoration: none;" title="Timeline"><i class="icon-note"></i></a></td>
                </tr>
                @endforeach
                @else
                <tr>
                  <td colspan="11" align="center">Tiada Maklumat</td>
                </tr>
                @endif
              </tbody>

            </table>
          </div>
        </section>
      </div>
    </div>

    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="myModalLabel">Ulasan</h4>
                </div>
                <div class="modal-body" id="showresults">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    @endif

  </div>
</div>
@endsection
