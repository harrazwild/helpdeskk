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

    var oTable = $('#audit').DataTable({
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
                 url: "{{ route('getAudit') }}"
               },
        columns: [
            { data: 'noAduan', name: 'audit_trail.application_no'},
            { data: 'penyelaras', name: 'a.name'},
            { data: 'description', name: 'audit_trail.description'},
            { data: 'perincian', name: 'audit_trail.category'},
            { data: 'tarikh', name: 'audit_trail.created_at'},
            { data: 'st', name: 'audit_trail.status'},
        ]
    });

    $('#search').on('keyup', function() {
        oTable.search( $(this).val() ).draw();
    });
    
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
                                    <input class="form-control" id="search" placeholder="Masukkan no aduan" type="text">
                                </div>
                                <div class="col-sm-4 text-left">
                                    <a href="{{ route('audit') }}" class="btn btn-warning">Semula</a>
                                </div>
                            </div>                    
                        </form>
                    </div>
                </section>

                <section class="panel panel-default">
                    <div class="panel-heading">Jejak Audit</div>
                    <div class="panel-body table-responsive">
                        <table class="table" id="audit">
                            <thead style="background-color: #62549a; color: #ffffff">
                            <tr>
                                <th width="8%">No Aduan</th>
                                <th width="15%">Pegawai</th>
                                <th>Keterangan</th>
                                <th width="30%">...</th>
                                <th width="10%">Tarikh</th>
                                <th width="18%">Status</th>
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