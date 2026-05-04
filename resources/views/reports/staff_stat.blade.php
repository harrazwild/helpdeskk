
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
  });
</script>
@php
if(isset($s)){
  $s = $s;
}else{
  $s = 0;
}

if(isset($sc)){
  $sc = $sc;
}else{
  $sc = 0;
}
@endphp 
<div class="ui-content-body">
  <div class="ui-container">

    <div class="row">
      <div class="col-sm-12">
        <section class="panel panel-default">
          <div class="panel-heading">Statistik Tindakan Pegawai Teknikal</div>
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
              <div class="form-group row">
                  <label class="col-sm-2 col-form-label">&nbsp;</label>
                  <div class="col-sm-10 text-left">
                    <input type="submit" class="btn btn-success" style="min-width:100px" value="Hantar">
                    <a href="{{ route('staff_stat') }}" class="btn btn-warning">Semula</a>
                  </div>
              </div>
        
            </form>

          </div>
        </section>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="text-right" style="margin-bottom: 5px; margin-right: 10px">
          <div class="btn-group">
            <button data-toggle="dropdown" class="btn btn-info dropdown-toggle" type="button" aria-expanded="false">Cetak <span class="caret"></span></button>
            <ul role="menu" class="dropdown-menu">
              <li><a href="{{ route('staffStatPDF', ['sDate'=>$sDate, 'eDate'=>$eDate, 'staff'=>$s, 'section'=>$sc]) }}" target="_blank">PDF</a></li>
              <li><a href="{{ route('staffStatExcel', ['sDate'=>$sDate, 'eDate'=>$eDate, 'staff'=>$s, 'section'=>$sc]) }}" target="_blank">Excel</a></li>
            </ul>
          </div>
        </div>

        <section class="panel panel-default">
          <div class="panel-body table-responsive">
            <table class="table table-border table-striped report_1">
              <thead>
                <tr class="bg-primary">
                  <th width="2%">Bil</th>
                  <th>Nama</th>
                  <th width="18%">Jawatan</th>
                  <th width="10%">Dalam Tindakan</th>
                  <th width="10%">Tindakan Selesai</th>
                  <th width="10%">Pengesahan Pengadu</th>
                  <th width="10%">Aduan Ditutup</th>
                  <th width="6%">Jumlah</th>
                </tr>
              </thead>
              <tbody>
                @php  
                $bil = 1;
                @endphp
                @if(!$p->isEmpty())
                @foreach($p as $row)
                <tr>
                  <td align="right">{{ $bil++ }}.</td>
                  <td>{{ $row->name }}</td>
                  <td>{{ $row->position_desc }}</td>
                  <td align="center">{{ App\Helper\Report::task($row->id, $s_date, $e_date, 1) }}</td>
                  <td align="center">{{ App\Helper\Report::task($row->id, $s_date, $e_date, 2) }}</td>
                  <td align="center">{{ App\Helper\Report::task($row->id, $s_date, $e_date, 3) }}</td>
                  <td align="center">{{ App\Helper\Report::task($row->id, $s_date, $e_date, 4) }}</td>
                  <td align="center"><strong>{{ App\Helper\Report::total_task($row->id, $s_date, $e_date,) }}</strong></td>
                </tr>
                @endforeach
                @else
                <tr>
                  <td colspan="8" align="center">Tiada Maklumat</td>
                </tr>
                @endif
              </tbody>
            </table>
          </div>
        </section>
      </div>
    </div>

  </div>
</div>
@endsection
