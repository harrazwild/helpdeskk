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
    
    var oTable = $('.table').DataTable({
        ordering: false,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Malay.json"
        },
        dom: 'tipr',
        "pageLength": 3
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
                                    <input class="form-control" name="search" id="search" placeholder="Masukkan no aduan, emel, nama pengadu" type="text" value="{{ $search }}">
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
                                <label for="status" class="col-sm-2 control-label">Tahun</label>
                                <div class="col-lg-2">
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
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">&nbsp;</label>
                                <div class="col-sm-10 text-left">
                                    <input type="submit" class="btn btn-success" style="min-width:100px" value="Hantar">
                                    <a href="{{ route('ontask') }}" class="btn btn-warning">Semula</a>
                                </div>
                            </div>
                    
                        </form>
                    </div>
                </section>

                <section class="panel panel-warning">
                    <div class="panel-heading">Senarai Aduan</div>
                    <div class="panel-body table-responsive">
                        <table class="table" id="complaint">
                            <thead style="background-color: #62549a; color: #ffffff">
                            <tr>
                                <th width="3%"></th>
                                <th width="4%">Bil</th>
                                <th width="8%">No Aduan</th>
                                <th>Nama Pengadu</th>
                                <th width="30%">Sektor/Bahagian</th>
                                <th width="10%">Tarikh Aduan</th>
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
                                    @if($data->status_id != 4) <!-- Jika Aduan Belum Selesai -->
                                    @if($color) <!-- Jika Aduan Lebih Tempoh -->
                                    <i class="fa fa-flag" {!! $color !!}></i>
                                    @endif
                                    @endif
                                </td>
                                <td align="right">{{ $bil++ }}.</td>
                                <td><strong>#{{ $data->application_no }}</strong></td>
                                <td>{{ $data->pengadu }}</td>
                                <td><strong>{{ $data->sector_desc }}</strong><br>{!! $data->department_desc.'<br>'.$lokasi !!}</td>
                                <td>{{ date('d-m-Y', strtotime($data->date_open)) }}</td>
                                <td>
                                @if($data->status_id == 1)
                                    <label class="label label-default">{{ $data->status_desc }}</label>
                                @elseif($data->status_id == 2 || $data->status_id == 3 || $data->status_id == 9 || $data->status_id == 11)
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
                                    @if(Auth::user()->role_id == 2)

                                        @if($data->status_id == 6 || $data->status_id == 7)
                                            <a href="{{ route('show_verify', Crypt::encrypt($data->id)) }}" style="text-decoration: none;" title="Kemaskini"><i class="icon-note"></i></a>
                                        @else
                                            <a href="{{ route('show_technical', Crypt::encrypt($data->id)) }}" style="text-decoration: none;" title="Kemaskini"><i class="icon-note"></i></a>
                                        @endif
                                    @elseif(Auth::user()->role_id == 4)
                                        <a href="{{ route('show_officer', Crypt::encrypt($data->id)) }}" style="text-decoration: none;" title="Papar"><i class="icon-note"></i></a>    
                                    @else
                                        <a href="{{ route('show_technical', Crypt::encrypt($data->id)) }}" style="text-decoration: none;" title="Kemaskini"><i class="icon-note"></i></a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="panel panel-danger">
                    <div class="panel-heading">Senarai Mesyuarat</div>
                    <div class="panel-body table-responsive">
                        <table class="table" id="complaint">
                            <thead style="background-color: #62549a; color: #ffffff">
                            <tr>
                                <th width="4%">Bil</th>
                                <th width="8%">No Aduan</th>
                                <th>Nama Pemohon</th>
                                <th width="30%">Lokasi Mesyuarat</th>
                                <th width="15%">Tarikh/Masa</th>
                                <th width="2%">Aktiviti</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                            $bil = 1;
                            @endphp
                            @foreach($meeting as $row)
                            @php
                            
                            $lokasi = '';
                            if($row->block != '')
                              $lokasi = "Blok ".$row->block;
                            if($row->level != '')
                              $lokasi .= ", ".$row->level;
                            if($row->zone != '')
                              $lokasi .= ", Zon ".$row->zone;

                            @endphp
                            <tr>
                                <td align="right">{{ $bil++ }}.</td>
                                <td><strong>#{{ $row->application_no }}</strong></td>
                                <td>{{ $row->pengadu }}</td>
                                <td><strong>{{ $row->location }}</strong><br>{{ $row->sector_desc }}<br>{!! $row->department_desc.'<br>'.$lokasi !!}</td>
                                <td>{!! App\Helper\Utilities::meetingTime($row->id) !!}</td>
                                <td align="center">
                                    <a href="{{ route('show_meeting', Crypt::encrypt($row->id)) }}" style="text-decoration: none;" title="Kemaskini"><i class="icon-note"></i></a>
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