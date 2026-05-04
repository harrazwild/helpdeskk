@extends('layout.mainlayout')

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
    .dataTables_filter 
    { 
        display: none; 
    }
    .dataTables_wrapper .dataTables_processing
    {
        border: 0px;
    }
</style>
<script type="text/javascript">
$(document).ready(function(){

    var oTable = $('#archive').DataTable({
        order: [[3, "asc"]],
        ordering: false,
        processing: true,
        serverSide: true,
        language: {
            processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span> ',
            "sEmptyTable":      "Tiada data",
            "sInfo":            "Paparan dari _START_ hingga _END_ dari _TOTAL_ rekod",
            "sInfoEmpty":       "Paparan 0 hingga 0 dari 0 rekod",
            "sInfoFiltered":    "(Ditapis dari jumlah _MAX_ rekod)",
            "sInfoPostFix":     "",
            "sInfoThousands":   ",",
            "sLengthMenu":      "Papar _MENU_ rekod",
            "sLoadingRecords":  "Diproses...",
            "sSearch":          "Carian:",
           "sZeroRecords":      "Tiada padanan rekod yang dijumpai.",
           "oPaginate": {
               "sFirst":        "Pertama",
               "sPrevious":     "Sebelum",
               "sNext":         "Seterusnya",
               "sLast":         "Akhir"
           },
           "oAria": {
               "sSortAscending":  ": diaktifkan kepada susunan lajur menaik",
               "sSortDescending": ": diaktifkan kepada susunan lajur menurun"
           }
            //url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Malay.json",
        },
        ajax:{
                 url: "{{ route('getArchive') }}",
                 data: function (d) {
                    d.search = $('#search').val();
                    d.u_task = $('#u_task').val();
                    d.status = $('#status').val();
                    d.tahun = $('#tahun').val();
                }
               },
        columns: [
            { data: 'noAduan', name: 'norujukan'},
            { data: 'detail_user', name: 'detail_user'},
            { data: 'lokasi', name: 'lokasi'},
            { data: 'Tarikh', name: 'Tarikh'},
            { data: 'helpdesk2', name: 'helpdesk2'},
            { data: 'Status', name: 'Status'},
            { data: 'action', name: 'action', orderable: false, searchable: false}
        ]
    });

    // $('#tahun').on('change', function(){
    //    oTable.columns(4).search( this.value ).draw();  
    // });

    // $('#status').on('change', function(){
    //    oTable.columns(5).search( this.value ).draw();  
    // });

    // $('#search').on('keyup', function() {
    //     oTable.search( this.value ).draw();
    // });
    
});
</script>
<div class="ui-content-body">  
    <div class="ui-container">
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <div class="panel-body table-responsive">
                        <form class="form-horizontal" method="get">
                            @csrf
                            <div class="form-group">
                                <label for="search" class="col-sm-2 control-label">Carian</label>
                                <div class="col-lg-4">
                                    <input class="form-control" name="search" id="search" placeholder="Masukkan no aduan, nama pengadu, maklumat aduan" type="text" value="{{ $search }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="u_task" class="col-sm-2 control-label">Pelaksana</label>
                                <div class="col-lg-4">
                                    <select name="u_task" id="u_task" class="form-control">
                                        <option value="">Sila Pilih</option>
                                        @foreach($staffs as $row)
                                        <option value="{{ $row->helpdesk2 }}" {{ $u_task == $row->helpdesk2 ? 'selected' : '' }}>{{ $row->helpdesk2 }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="status" class="col-sm-2 control-label">Status</label>
                                <div class="col-lg-4">
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Sila Pilih</option>
                                        <option value="Aduan Baru" {{ $st == 'Aduan Baru' ? 'selected' : '' }}>Aduan Baru</option>
                                        <option value="Dalam Proses" {{ $st == 'Dalam Proses' ? 'selected' : '' }}>Dalam Proses</option>
                                        <option value="Terus ke Juruteknik" {{ $st == 'Terus ke Juruteknik' ? 'selected' : '' }}>Terus ke Juruteknik</option>
                                        <option value="Terus ke Pegawai Berkaitan(Sila Lihat pada Pegawai Bertanggungjawab)" {{ $st == 'Terus ke Pegawai Berkaitan(Sila Lihat pada Pegawai Bertanggungjawab)' ? 'selected' : '' }}>Terus ke Pegawai</option>
                                        <option value="Selesai" {{ $st == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="status" class="col-sm-2 control-label">Tahun</label>
                                <div class="col-lg-2">
                                    <select name="tahun" id="tahun" class="form-control">
                                    <option value="">Sila Pilih</option>    
                                    @php
                                    $start_years = 2012;
                                    $curr_years = 2021;
                                    
                                    $years = range($curr_years, $start_years);
                                    @endphp
                                    @foreach($years as $year)
                                    <option value="{{ $year }}" {{ $y == $year ? 'selected' : '' }} >{{ $year }}</option>;   
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">&nbsp;</label>
                                <div class="col-sm-10 text-left">
                                    <input type="submit" class="btn btn-success" style="min-width:100px" value="Hantar">
                                    <a href="{{ route('archive') }}" class="btn btn-warning">Semula</a>
                                </div>
                            </div>                   
                        </form>
                    </div>
                </section>

                <section class="panel panel-default">
                    <div class="panel-heading">Senarai Arkib</div>
                    <div class="panel-body table-responsive">
                        <table class="table" id="archive">
                            <thead style="background-color: #62549a; color: #ffffff">
                            <tr>
                                <th width="8%">No Aduan</th>
                                <th>Nama Pengadu</th>
                                <th width="30%">Sektor/Bahagian</th>
                                <th width="10%">Tarikh Aduan</th>
                                <th>Pegawai Teknikal</th>
                                <th width="18%">Status</th>
                                <th width="2%">Aktiviti</th>
                            </tr>
                            </thead>
                            <tbody>   
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

        </div>

    </div>
</div>
@endsection