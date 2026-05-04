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
    tr.group, tr.group:hover 
    {
        background-color: #f5f5f5 !important;
    }
</style>
<script type="text/javascript">
$(document).ready(function(){
    
    var groupColumn = 5;

    var oTable = $('#complaint').DataTable({
        
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Malay.json"
        },
        dom: 'tipr',
        columnDefs: [
            { "visible": false, "targets": groupColumn }
        ],
        orderFixed: false,
        //order: [[ groupColumn, 'asc' ]],
        displayLength: 25,
        drawCallback: function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last = null;
 
            api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="6">'+group+'</td></tr>'
                    );
 
                    last = group;
                }
            } );
        }
    });


    $('#sector').on('change', function(){
       oTable.columns(2).search( this.value ).draw();  
    });

    $('#status').on('change', function(){
       oTable.columns(4).search( this.value ).draw();  
    });

    $('#search').on('keyup', function() {
        oTable.search( this.value ).draw();
    });
    
});
</script>
<div class="ui-content-body">  
    <div class="ui-container">

        <div class="row">
            <!--daily visit start-->
            <div class="col-md-4  ">
                <div class="panel panel-primary">
                    <header class="panel-heading panel-border">
                        Jumlah Aduan Bagi Seksyen
                        <span class="tools pull-right">
                            <a class="refresh-box fa fa-repeat" href="javascript:;"></a>
                            <a class="collapse-box fa fa-chevron-down" href="javascript:;"></a>
                        </span>
                    </header>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <h1>{{ $total->new }}</h1>
                                <strong class="text-uppercase">Aduan Baru</strong>
                            </div>
                            <div class="col-md-4 text-center">
                                <h1>{{ $total->dt }}</h1>
                                <strong class="text-uppercase">Dalam Tindakan</strong>
                            </div>
                            <div class="col-md-4 text-center">
                                <h1>{{ $total->done }}</h1>
                                <strong class="text-uppercase">Selesai</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--daily visit end-->
            <!--Visitor Graph start-->
            <div class="col-md-4 ">
                <div class="panel panel-info">
                    <header class="panel-heading panel-border">
                        Jumlah Keseluruhan Aduan
                        <span class="tools pull-right">
                            <a class="refresh-box fa fa-repeat" href="javascript:;"></a>
                            <a class="collapse-box fa fa-chevron-down" href="javascript:;"></a>
                        </span>
                    </header>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <h1>{{ $totalAll->new }}</h1>
                                <strong class="text-uppercase">Aduan Baru</strong>
                            </div>
                            <div class="col-md-4 text-center">
                                <h1>{{ $totalAll->dt }}</h1>
                                <strong class="text-uppercase">Dalam Tindakan</strong>
                            </div>
                            <div class="col-md-4 text-center">
                                <h1>{{ $totalAll->done }}</h1>
                                <strong class="text-uppercase">Selesai</strong>
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
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label for="search" class="col-sm-2 control-label">Carian</label>
                                <div class="col-lg-4">
                                    <input class="form-control" id="search" placeholder="Masukkan no aduan, emel, nama pengadu" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="status" class="col-sm-2 control-label">Status</label>
                                <div class="col-lg-2">
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Sila Pilih</option>
                                        @foreach($status as $row)
                                        <option value="{{ $row->status_desc }}" >{{ $row->status_desc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="sector" class="col-sm-2 control-label">Sektor</label>
                                <div class="col-lg-3">
                                    <select name="sector" id="sector" class="form-control">
                                        <option value="">Sila Pilih</option>
                                        @foreach($sectors as $row)
                                        <option value="{{ $row->sector_desc }}" >{{ $row->sector_desc }}</option>
                                        @endforeach
                                    </select>
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
                                <th width="1%"></th>
                                <th width="8%">No Aduan</th>
                                <th>Nama Pengadu</th>
                                <th width="30%">Sektor/Bahagian</th>
                                <th width="10%">Tarikh Aduan</th>
                                <th width="10%">Status</th>
                                <th width="2%">Aktiviti</th>
                            </tr>
                            </thead>
                            <tbody> 
                            @foreach($complaints as $data)
                            @php
                            $color = App\Helper\Utilities::task_day($data->id);
                            @endphp
                            <tr>
                                <td>
                                    @if($data->status_id != 4) <!-- Jika Aduan Belum Selesai -->
                                    @if($color) <!-- Jika Aduan Lebih Tempoh -->
                                    <i class="fa fa-flag" {!! $color !!}></i>
                                    @endif
                                    @endif
                                </td>
                                <td align="center"><strong>#{{ $data->application_no }}</strong></td>
                                <td>{{ $data->name }}</td>
                                <td><strong>{{ $data->sector_desc }}</strong><br>{{ $data->department_desc }}</td>
                                <td align="center">{{ date('d-m-Y', strtotime($data->date_open)) }}</td>
                                <td>
                                @if($data->status_id == 1)
                                    <strong>{{ $data->status_desc }}</strong>
                                @elseif($data->status_id == 2)
                                    <strong>{{ $data->status_desc }}</strong>
                                @else
                                    <strong>{{ $data->status_desc }}</strong>
                                @endif    
                                </td>
                                <td align="center"><a href="/complaintlist/{{ Crypt::encrypt($data->id) }}" style="text-decoration: none;" title="Serah Tugas"><i class="@if($data->status_id == 4) icon-eye @else icon-note @endif"></i></a></td>
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