@extends('layout.userlayout')

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
    
  var oTable = $('#complaint').DataTable({
    ordering: false,
    language: {
      url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Malay.json"
    },
    dom: 'tipr'
  });

  $(".delete").click(function(e){
        
    e.preventDefault();
    var id = $(this).attr("data-id");

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

        var url = '{{ route("del_complaint") }}';

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
 
});
</script>
<div class="ui-content-body">  
    <div class="ui-container">

      <div class="row">
        <div class="col-sm-12">
          <section class="panel">
            <header class="panel-heading">
              Carian
            </header>
            <div class="panel-body table-responsive">
              <form class="form-horizontal" method="get">
                @csrf
                <div class="form-group">
                  <label for="search" class="col-sm-2 control-label">Carian</label>
                  <div class="col-lg-4">
                    <input class="form-control" name="search" id="search" placeholder="Masukkan no aduan atau keterangan" type="text" value="{{ $search }}">
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="status" class="col-sm-6 control-label">Status</label>
                      <div class="col-lg-6">
                        <select name="status" id="status" class="form-control">
                          <option value="">Sila Pilih</option>
                          <option value="1" {{ $st == '1' ? 'selected' : '' }} >Aduan Baru</option>
                          <option value="2" {{ $st == '2' ? 'selected' : '' }} >Dalam Tindakan</option>
                          <option value="3" {{ $st == '3' ? 'selected' : '' }} >Tindakan Pegawai</option>
                          <option value="4" {{ $st == '4' ? 'selected' : '' }} >Pengesahan Pengadu</option>
                          <option value="6" {{ $st == '6' ? 'selected' : '' }} >Disahkan Selesai</option>
                          <option value="7" {{ $st == '7' ? 'selected' : '' }} >Tidak Disahkan Selesai</option>
                          <option value="8" {{ $st == '8' ? 'selected' : '' }} >Aduan Ditutup</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="status" class="col-sm-1 control-label">Tahun</label>
                      <div class="col-lg-3">
                        <select name="tahun" id="tahun" class="form-control">
                        @php
                        $start_years = 2020;
                        $curr_years = date('Y');
                        
                        $years = range($curr_years, $start_years);
                        @endphp
                        @foreach($years as $year)
                        <option value="{{ $year }}" {{ $y == $year ? 'selected' : '' }} >{{ $year }}</option>;   
                        @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label">&nbsp;</label>
                  <div class="col-sm-10 text-left">
                    <input type="submit" class="btn btn-success" style="min-width:100px" value="Hantar">
                    <a href="{{ route('home') }}" class="btn btn-warning">Semula</a>
                  </div>
                </div>
              </form>
            </div>
          </section>

          <section class="panel panel-default">
            <div class="panel-heading">Senarai Aduan</div>
            <div class="panel-body table-responsive">
              <table class="table" id="complaint">
                <thead style="background-color: #62549a; color: #ffffff">
                <tr>
                  <th width="4%">Bil</th>
                  <th width="8%">No Aduan</th>
                  <th width="15%">Kategori</th>
                  <th>Keterangan Aduan</th>
                  <th width="10%">Tarikh Aduan</th>
                  <th width="10%">Status</th>
                  <th width="8%">Aktiviti</th>
                </tr>
                </thead>
                <tbody>
                @php
                $bil = 1;
                @endphp
                @foreach($complaints as $data)
                <tr>
                  <td align="right">{{ $bil++ }}.</td>
                  <td><strong>#{{ $data->application_no }}</strong></td>
                  <td>{{ $data->category_desc }}</td>
                  <td>
                  @if($data->category_id == 12)
                    Permohonan peralatan ICT untuk sidang mesyuarat
                  @else
                    {{ $data->remarks }}
                  @endif  
                  </td>
                  <td>{{ date('d-m-Y', strtotime($data->date_open)) }}</td>
                  <td>
                  @if($data->status_id == 1)
                      <label class="label label-default">{{ $data->status_desc }}</label>
                  @elseif($data->status_id == 2 || $data->status_id == 3 || $data->status_id == 9)
                      <label class="label label-danger">{{ $data->status_desc }}</label>
                  @elseif($data->status_id == 4 || $data->status_id == 5)
                      <label class="label label-info">Tindakan Selesai</label>
                  @elseif($data->status_id == 6 || $data->status_id == 7)
                      <label class="label label-primary">{{ $data->status_desc }}</label>        
                  @elseif($data->status_id == 8)
                      <label class="label label-success">Aduan Ditutup</label>
                  @endif     
                  </td>
                  <td align="center">
                    <a href="{{ route('show_complaint', Crypt::encrypt($data->id)) }}" style="text-decoration: none;" title="Papar"><i class="icon-magnifier"></i></a>&nbsp;
                    @if($data->status_id == 1)
                    <a href="{{ route('edit_complaint', Crypt::encrypt($data->id)) }}" style="text-decoration: none;" title="Kemaskini"><i class="icon-note"></i></a>
                    <a class="delete" data-id="{{ $data->id }}" style="text-decoration: none;" title="Hapus"><i class="icon-trash"></i></a>
                    @endif    
                  </td>
                </tr>
                @endforeach
                </tbody>
              </table>
            </div>
          </section>
        </div>

      </div>

    </div>
</div>
@endsection