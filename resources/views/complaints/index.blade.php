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
</style>
<script type="text/javascript">
$(document).ready(function(){
    
    $('.modal').on('hidden.bs.modal', function(){
        $(this).find('form')[0].reset();
    });
    
    var oTable = $('#complaint').DataTable({
        ordering: false,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Malay.json"
        },
        dom: 'tipr'
    });

    $('#selectAll').on('click', function() {
        var isChecked = $(this).prop('checked');
        oTable.$('.complaint-checkbox').prop('checked', isChecked);
    });

    $('#deleteSelected').on('click', function() {
        var ids = [];
        oTable.$('.complaint-checkbox:checked').each(function() {
            ids.push($(this).val());
        });

        if (ids.length === 0) {
            alert('Sila pilih sekurang-kurangnya satu aduan untuk dipadam.');
            return;
        }

        if (confirm('Adakah anda pasti untuk memadam aduan yang dipilih?')) {
            $.ajax({
                url: '{{ route("complaintDelete") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: ids
                },
                success: function(response) {
                    if (response == 1) {
                        alert('Aduan berjaya dipadam.');
                        location.reload();
                    } else {
                        alert('Gagal memadam aduan.');
                    }
                },
                error: function() {
                    alert('Berlaku ralat, sila cuba lagi.');
                }
            });
        }
    });

    $('.time').on('click', function () {
        var id = $(this).attr("data-id");
        var url = '{{ route("getTimeline", ":id") }}';
        url = url.replace(':id', id );
        
        $('#logID').val(id);

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
<div class="ui-content-body">  
    <div class="ui-container">

        <div class="row">
            
            <!--daily visit end-->
            <!--Visitor Graph start-->
            <div class="col-md-12">
                <div class="panel panel-info">
                    <header class="panel-heading panel-border">
                        Pecahan Aduan Mengikut Status
                        <span class="tools pull-right">
                            <a class="refresh-box fa fa-repeat" href="javascript:;"></a>
                            <a class="collapse-box fa fa-chevron-down" href="javascript:;"></a>
                        </span>
                    </header>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-2 text-center" style="border-right: 1px solid #ddd">
                                <h1>{{ $task1 }}</h1>
                                <strong class="text-uppercase"><small>Sendiri</small></strong>
                            </div>
                            <div class="col-md-2 text-center" style="border-right: 1px solid #ddd">
                                <h1>{{ $total->new }}</h1>
                                <strong class="text-uppercase"><small>Aduan Baru</small></strong>
                            </div>
                            <div class="col-md-2 text-center" style="border-right: 1px solid #ddd">
                                <h1>{{ $total->dt }}</h1>
                                <strong class="text-uppercase"><small>Dalam Tindakan</small></strong>
                            </div>
                            <div class="col-md-2 text-center" style="border-right: 1px solid #ddd">
                                <h1>{{ $total->done }}</h1>
                                <strong class="text-uppercase"><small>Tindakan Selesai</small></strong>
                            </div>
                            <div class="col-md-2 text-center" style="border-right: 1px solid #ddd">
                                <h1>{{ $total->verify }}</h1>
                                <strong class="text-uppercase"><small>Pengesahan Pengadu</small></strong>
                            </div>
                            <div class="col-md-2 text-center">
                                <h1>{{ $total->close }}</h1>
                                <strong class="text-uppercase"><small>Aduan Ditutup</small></strong>
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
                    <header class="panel-heading">
                        Carian
                    </header>
                    <div class="panel-body table-responsive">
                        <form class="form-horizontal" method="get">
                            @csrf
                            <div class="form-group">
                                <label for="search" class="col-sm-2 control-label">Carian</label>
                                <div class="col-lg-4">
                                    <input class="form-control" name="search" id="search" placeholder="Masukkan no aduan, emel, nama pengadu, maklumat aduan" type="text" value="{{ $search }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="sector" class="col-sm-2 control-label">Sektor</label>
                                <div class="col-lg-4">
                                    <select name="sector" id="sector" class="form-control">
                                        <option value="">Sila Pilih</option>
                                        @foreach($sectors as $row)
                                        <option value="{{ $row->sector_code }}" {{ $sec == $row->sector_code ? 'selected' : '' }} >{{ $row->sector_desc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="u_task" class="col-sm-2 control-label">Pelaksana</label>
                                <div class="col-lg-4">
                                    <select name="u_task" id="u_task" class="form-control">
                                        <option value="">Sila Pilih</option>
                                        @foreach($staffs as $row)
                                        <option value="{{ $row->id }}" {{ $u_task == $row->id ? 'selected' : '' }} >{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                   <div class="form-group">
                                        <label for="status" class="col-sm-6 control-label">Bulan</label>
                                        <div class="col-lg-6">
                                            <select name="bulan" id="bulan" class="form-control">
                                            <option value="" >Sila Pilih</option>;   
                                            <option value="01" {{ $m == '01' ? 'selected' : '' }} >Januari</option>;   
                                            <option value="02" {{ $m == '02' ? 'selected' : '' }} >Februari</option>;   
                                            <option value="03" {{ $m == '03' ? 'selected' : '' }} >Mac</option>;   
                                            <option value="04" {{ $m == '04' ? 'selected' : '' }} >April</option>;   
                                            <option value="05" {{ $m == '05' ? 'selected' : '' }} >Mei</option>;   
                                            <option value="06" {{ $m == '06' ? 'selected' : '' }} >Jun</option>;   
                                            <option value="07" {{ $m == '07' ? 'selected' : '' }} >Julai</option>;   
                                            <option value="08" {{ $m == '08' ? 'selected' : '' }} >Ogos</option>;   
                                            <option value="09" {{ $m == '09' ? 'selected' : '' }} >September</option>;   
                                            <option value="10" {{ $m == '10' ? 'selected' : '' }} >Oktober</option>;   
                                            <option value="11" {{ $m == '11' ? 'selected' : '' }} >November</option>;   
                                            <option value="12" {{ $m == '12' ? 'selected' : '' }} >Disember</option>;   
                                            </select>
                                        </div>
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status" class="col-sm-1 control-label">Tahun</label>
                                        <div class="col-lg-3">
                                            <select name="tahun" id="tahun" class="form-control">
                                            <option value="">Sila Pilih</option>
                                            @php
                                            $start_years = 2020;
                                            $next_years = date('Y') + 1;
                                            
                                            $years = range($next_years, $start_years);
                                            @endphp
                                            @foreach($years as $year)
                                            <option value="{{ $year }}" {{ $y == $year ? 'selected' : '' }} >{{ $year }}</option>;   
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="status" class="col-sm-2 control-label">Status</label>
                                <div class="col-lg-4">
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Sila Pilih</option>
                                        <option value="1" {{ $st == 1 ? 'selected' : '' }}>Aduan Baru</option>
                                        <option value="2" {{ $st == 2 ? 'selected' : '' }}>Dalam Tindakan</option>
                                        <option value="3" {{ $st == 3 ? 'selected' : '' }}>Tindakan Pegawai</option>
                                        <option value="9" {{ $st == 9 ? 'selected' : '' }}>Tindakan Pembekal</option>
                                        <option value="4" {{ $st == 4 ? 'selected' : '' }}>Tindakan Selesai</option>
                                        <option value="6" {{ $st == 6 ? 'selected' : '' }}>Disahkan Selesai</option>
                                        <option value="7" {{ $st == 7 ? 'selected' : '' }}>Tidak Disahkan Selesai</option>
                                        <option value="8" {{ $st == 8 ? 'selected' : '' }}>Aduan Ditutup</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">&nbsp;</label>
                                <div class="col-sm-10 text-left">
                                    <input type="submit" class="btn btn-success" style="min-width:100px" value="Hantar">
                                    <a href="{{ route('complaintlist') }}" class="btn btn-warning">Semula</a>
                                </div>
                            </div>
                    
                        </form>
                    </div>
                </section>

                <section class="panel panel-default">
                    <div class="panel-heading">
                        Senarai Aduan
                        <span class="pull-right">
                            <button class="btn btn-danger btn-xs" id="deleteSelected">Padam Pilihan</button>
                        </span>
                    </div>
                    <div class="panel-body table-responsive">
                        <table class="table" id="complaint">
                            <thead style="background-color: #62549a; color: #ffffff">
                            <tr>
                                <th width="2%"><input type="checkbox" id="selectAll"></th>
                                <th width="3%"></th>
                                <th width="4%">Bil</th>
                                <th width="8%">No Aduan</th>
                                <th>Nama Pengadu</th>
                                <th width="30%">Sektor/Bahagian</th>
                                <th width="10%">Tarikh Aduan</th>
                                <th width="15%">Pelaksana</th>
                                <th width="10%">Status</th>
                                <th width="2%">Aktiviti</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                            $bil = 1;
                            @endphp
                            @foreach($complaints as $data)
                            @php
                            $color = App\Helper\Utilities::task_day($data->id);

                            $lokasi = '';
                            if($data->block != '')
                              $lokasi = "Blok ".$data->block;
                            if($data->level != '')
                              $lokasi .= ", ".$data->level;
                            if($data->zone != '')
                              $lokasi .= ", Zon ".$data->zone;

                            @endphp
                            <tr>
                                <td align="center">
                                    <input type="checkbox" class="complaint-checkbox" value="{{ $data->id }}">
                                </td>
                                <td align="center">
                                    @if($data->status_id < 4 || $data->status_id == 9) <!-- Jika Aduan Belum Selesai -->
                                    @if($color) <!-- Jika Aduan Lebih Tempoh -->
                                    <i class="fa fa-flag" {!! $color !!}></i>
                                    @endif
                                    @endif
                                </td>
                                <td align="right">{{ $bil++ }}.</td>
                                <td><strong>#{{ $data->application_no }}</strong></td>
                                <td>{{ $data->name }}<br>
                                    @if($data->position_desc != '')
                                    {{ $data->position_desc }}
                                    @endif
                                    @if($data->grade_desc != '')
                                    ({{ $data->grade_desc }})
                                    @endif
                                </td>
                                <td><strong>{{ $data->sector_desc }}</strong><br>{!! $data->department_desc.'<br>'.$lokasi !!}</td>
                                <td>{{ date('d-m-Y', strtotime($data->date_open)) }}</td>
                                <td>{{ $data->pegawai_pelaksana }}</td>
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
                                    @if($data->status_id == 8) <!-- jika selesai -->
                                        <a href="{{ route('show_done', Crypt::encrypt($data->id)) }}" style="text-decoration: none;" title="Papar"><i class="icon-eye"></i></a>
                                    @else <!-- jika belum selesai -->    
                                        
                                        @if(Auth::user()->role_id == 2)

                                            @if($data->status_id == 6 || $data->status_id == 7)
                                                <a href="{{ route('show_verify', Crypt::encrypt($data->id)) }}" style="text-decoration: none;" title="Kemaskini"><i class="icon-note"></i></a>
                                            @else
                                                @if($data->id_pelaksana == Auth::user()->id && ($data->status_id == 2 || $data->status_id == 9))
                                                    <a href="{{ route('show_technical', Crypt::encrypt($data->id)) }}" style="text-decoration: none;" title="Kemaskini"><i class="icon-note"></i></a>
                                                @else
                                                    <a href="{{ route('show_coordinator', Crypt::encrypt($data->id)) }}" style="text-decoration: none;" title="Kemaskini"><i class="icon-note"></i></a>
                                                @endif
                                            @endif

                                        @elseif(Auth::user()->role_id == 3)

                                            @if($data->id_pelaksana == Auth::user()->id && ($data->status_id == 2 || $data->status_id == 9))
                                                <a href="{{ route('show_technical', Crypt::encrypt($data->id)) }}" style="text-decoration: none;" title="Kemaskini"><i class="icon-note"></i></a>
                                            @else    
                                                <a href="{{ route('show_disabled', Crypt::encrypt($data->id)) }}" style="text-decoration: none;" title="Papar"><i class="icon-note"></i></a>
                                            @endif

                                        @elseif(Auth::user()->role_id == 4)

                                            <a href="{{ route('show_officer', Crypt::encrypt($data->id)) }}" style="text-decoration: none;" title="Papar"><i class="icon-note"></i></a>

                                        @endif
                                    @endif
                                    &nbsp;<a href="#" data-id="{{ $data->id }}" class="time" data-toggle="modal" data-target="#myModal" style="text-decoration: none;" title="Timeline"><i class="icon-clock"></i></a>
                                </td>
                            </tr>
                            @endforeach
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
                        <h4 class="modal-title" id="myModalLabel">Jejak Audit</h4>
                    </div>
                    <div class="modal-body" style="background-color: #eaeef3;" id="showresults">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <a href="{{ route('auditPDF') }}" onclick="event.preventDefault();
                        document.getElementById('cetak-form').submit();" class="btn btn-info">Cetak</a>
                        <form id="cetak-form" action="{{ route('auditPDF') }}" method="POST" class="d-none">
                            @csrf
                            <input type="hidden" id="logID" name="logID">
                        </form>  
                        
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection