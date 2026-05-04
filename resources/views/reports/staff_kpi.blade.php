
@extends('layout.mainlayout')

@section('content')
<style>
tbody.collapse.in 
{
  display: table-row-group;
}
.modal-lg
{
  width: 1200px;
}
.modal-title
{
  color: #fff;
}
.modal-dialog
{
  margin-top: 200px;
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

    // Datatable
    var oTable = $('.dtable').DataTable({
      ordering: false,
      language: {
        url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Malay.json"
      },
      dom: 'tipr'
    });

    // Reset table dlm modal kepada kosong
    $('#threeModal').on('hidden.bs.modal', function(){
        $('#tdays').html("");
    });

    // Reset table dlm modal kepada kosong
    $('#fiveeModal').on('hidden.bs.modal', function(){
        $('#fdays').html("");
    });

    // Papar modal bila klik angka di ruang lebih 3 hari
    $('.threeBtn').on('click', function(){
      var data = $(this).data('id');

      $.ajax({
        url: "{{ route('getThreeDays') }}",
        data: { data: data},
        type: 'get',
        dataType: 'json',
        success: function(res){
          //console.log(res);
          var trHTML = '';
          if(res && res.length != 0){
            $.each(res, function (key,value) {
              var date1 = moment(value.date_open);
              var newDate1 = date1.format('DD-MM-YYYY');

              if (!value.date_job_done) {
                var newDate2 = '-';
              } else {
                var date2 = moment(value.date_job_done);
                var newDate2 = date2.format('DD-MM-YYYY');
              }

              trHTML += 
                  '<tr><td><b>#' + value.application_no + '</b></td><td>' + value.name + '</td><td>' + newDate1 + '</td><td>' + newDate2 + '</td><td>' + value.status_desc + '</td></tr>';     
            });

            $('#tdays').append(trHTML);
            $('#threeModal').modal('show');
          }else{
            trHTML = 
                  '<tr><td colspan="5" align="center">Tiada Pegawai Pelulus</td></tr>';  
            $('#tdays').append(trHTML);
            $('#threeModal').modal('show');
          }
        },
        error: function (res) {
          console.log(res);
        }
      }); 
    });

    $('.fiveBtn').on('click', function(){
      var data = $(this).data('id');

      $.ajax({
        url: "{{ route('getFiveDays') }}",
        data: { data: data},
        type: 'get',
        dataType: 'json',
        success: function(res){
          //console.log(res);
          var trHTML = '';
          if(res && res.length != 0){
            $.each(res, function (key,value) {
              var date1 = moment(value.date_open);
              var newDate1 = date1.format('DD-MM-YYYY');

              if (!value.date_job_done) {
                var newDate2 = '-';
              } else {
                var date2 = moment(value.date_job_done);
                var newDate2 = date2.format('DD-MM-YYYY');
              }

              trHTML += 
                  '<tr><td><b>#' + value.application_no + '</b></td><td>' + value.name + '</td><td>' + newDate1 + '</td><td>' + newDate2 + '</td><td>' + value.status_desc + '</td></tr>';     
            });

            $('#fdays').append(trHTML);
            $('#fiveModal').modal('show');
          }else{
            trHTML = 
                  '<tr><td colspan="5" align="center">Tiada Pegawai Pelulus</td></tr>';  
            $('#fdays').append(trHTML);
            $('#fiveModal').modal('show');
          }    
        },
        error: function (res) {
          console.log(res);
        }
      }); 
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
                    <a href="{{ route('staff_kpi') }}" class="btn btn-warning">Semula</a>
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
              <li><a href="{{ route('staffKpiPDF', ['sDate'=>$sDate, 'eDate'=>$eDate, 'staff'=>$s]) }}" target="_blank">PDF</a></li>
              <li><a href="{{ route('staffKpiExcel', ['sDate'=>$sDate, 'eDate'=>$eDate, 'staff'=>$s]) }}" target="_blank">Excel</a></li>
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
                  <th width="25%">Jawatan</th>
                  <th width="10%">Lebih 3 Hari</th>
                  <th width="10%">Lebih 5 Hari</th>
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
                  <td align="center">
                    <a href="#" class="threeBtn" data-id="{{ $row->id.'_'.$s_date.'_'.$e_date }}">{{ App\Helper\Report::threedays($row->id, $s_date, $e_date) }}</a>
                  </td>
                  <td align="center">
                    <a href="#" class="fiveBtn" data-id="{{ $row->id.'_'.$s_date.'_'.$e_date }}">{{ App\Helper\Report::fivedays($row->id, $s_date, $e_date) }}</a>
                  </td>
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

    <!---------- edit modal form ---------->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="threeModal" class="modal fade" style="display: none;">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-info">
            <h4 class="modal-title">Aduan Lebih 3 Hari</h4>
          </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table class="table table-border">
                <thead>
                  <tr>
                    <th>No. Permohonan</th>
                    <th>Maklumat Pengadu</th>
                    <th>Tarikh Aduan</th>
                    <th>Tarikh Selesai</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody id="tdays">
                  
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-warning" data-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>
    <!---------- !end edit modal form ---------->

    <!---------- edit modal form ---------->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="fiveModal" class="modal fade" style="display: none;">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-info">
            <h4 class="modal-title">Aduan Lebih 5 Hari</h4>
          </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table class="table table-border">
                <thead>
                  <tr>
                    <th>No. Permohonan</th>
                    <th>Maklumat Pengadu</th>
                    <th>Tarikh Aduan</th>
                    <th>Tarikh Selesai</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody id="fdays">
                  
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-warning" data-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>
    <!---------- !end edit modal form ---------->

  </div>
</div>
@endsection
