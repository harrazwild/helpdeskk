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

    var oTable = $('#complaint').DataTable({
        //order: [[4, "asc"]],
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
                url: "{{ route('getComplaint') }}",
                type:'post',
                'beforeSend': function (request) {
                    request.setRequestHeader("X-CSRF-TOKEN", '{{ csrf_token() }}');
                },
                data: function (d){
                    d.search = $('#search').val();
                    d.sector = $('#sector').val();
                    d.status = $('#status').val();
                    d.tahun = $('#tahun').val();
                }
            },
        columns: [
            { data: 'flag', name: 'flag'},
            { data: 'app_no', name: 'application_no'},
            { data: 'name', name: 'name'},
            { data: 'location', name: 'location'},
            { data: 'tarikh', name: 'date_open'},
            { data: 'status', name: 'status_id'},
            { data: 'ic_number', name: 'ic_number'},
            { data: 'action', name: 'action', orderable: false, searchable: false}
        ]
    });

    $('#sector').on('change', function(){
        //oTable.columns(3).search( this.value ).draw();  
        oTable.draw();
    });

    $('#status').on('change', function(){
    //    oTable.columns(5).search( this.value ).draw();  
        oTable.draw();
    });

    $('#tahun').on('change', function(){
    //    oTable.columns(5).search( this.value ).draw();  
        oTable.draw();
    });

    $('#search').on('keyup', function() {
        //oTable.search( this.value ).draw();
        oTable.draw();
    });
    
});
</script>
<div class="ui-content-body">  
    <div class="ui-container">

        <div class="row">
            <!--daily visit start-->
            <div class="col-md-2">
                <div class="panel panel-primary">
                    <header class="panel-heading panel-border">
                        Jumlah Aduan
                        <span class="tools pull-right">
                            <a class="refresh-box fa fa-repeat" href="javascript:;"></a>
                            <a class="collapse-box fa fa-chevron-down" href="javascript:;"></a>
                        </span>
                    </header>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6 text-center" style="border-right: 1px solid #ddd">
                                <h1>{{ $task1->self }}</h1>
                                <strong class="text-uppercase"><small>Sendiri</small></strong>
                            </div>
                            <div class="col-md-6 text-center">
                                <h1>{{ $task2->team }}</h1>
                                <strong class="text-uppercase"><small>Seksyen</small></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--daily visit end-->
            <!--Visitor Graph start-->
            <div class="col-md-4">
                <div class="panel panel-info">
                    <header class="panel-heading panel-border">
                        Jumlah Aduan Bagi Seksyen
                        <span class="tools pull-right">
                            <a class="refresh-box fa fa-repeat" href="javascript:;"></a>
                            <a class="collapse-box fa fa-chevron-down" href="javascript:;"></a>
                        </span>
                    </header>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4 text-center" style="border-right: 1px solid #ddd">
                                <h1>{{ $total->new }}</h1>
                                <strong class="text-uppercase"><small>Aduan Baru</small></strong>
                            </div>
                            <div class="col-md-4 text-center" style="border-right: 1px solid #ddd">
                                <h1>{{ $total->dt }}</h1>
                                <strong class="text-uppercase"><small>Dalam Tindakan</small></strong>
                            </div>
                            <div class="col-md-4 text-center">
                                <h1>{{ $total->done }}</h1>
                                <strong class="text-uppercase"><small>Selesai</small></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--Visitor Graph end-->
        </div>

        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <div class="panel-body table-responsive">
                        <form class="form-horizontal" method="get">
                            @csrf
                            <div class="form-group">
                                <label for="search" class="col-sm-2 control-label">Carian</label>
                                <div class="col-lg-4">
                                    <input class="form-control" id="search" placeholder="Masukkan no aduan, emel, nama pengadu" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="sector" class="col-sm-2 control-label">Sektor</label>
                                <div class="col-lg-3">
                                    <select name="sector" id="sector" class="form-control">
                                        <option value="">Sila Pilih</option>
                                        @foreach($sectors as $row)
                                        <option value="{{ $row->sector_code }}" {{ $sec == $row->sector_code ? 'selected' : '' }} >{{ $row->sector_desc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status" class="col-sm-6 control-label">Status</label>
                                        <div class="col-lg-6">
                                            <select name="status" id="status" class="form-control">
                                                <option value="">Sila Pilih</option>
                                                @foreach($status as $row)
                                                <option value="{{ $row->id }}" {{ $st == $row->id ? 'selected' : '' }} >{{ $row->status_desc }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status" class="col-sm-2 control-label">Tahun</label>
                                        <div class="col-lg-2">
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
                                    <a href="/complaintlist" class="btn btn-warning">Semula</a>
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
                                <th width="3%"></th>
                                <th width="8%">No Aduan</th>
                                <th>Nama Pengadu</th>
                                <th width="30%">Sektor/Bahagian</th>
                                <th width="10%">Tarikh Aduan</th>
                                <th width="10%">Status</th>
                                <th width="10%">Tindakan</th>
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