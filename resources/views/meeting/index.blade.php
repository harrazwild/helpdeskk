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
    var oTable = $('#complaint').DataTable({
        ordering: false,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Malay.json"
        },
        dom: 'tipr'
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
                                        <option value="1" {{ $st == 1 ? 'selected' : '' }}>Permohonan Baru</option>
                                        <option value="2" {{ $st == 2 ? 'selected' : '' }}>Dalam Tindakan</option>
                                        <option value="8" {{ $st == 8 ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">&nbsp;</label>
                                <div class="col-sm-10 text-left">
                                    <input type="submit" class="btn btn-success" style="min-width:100px" value="Hantar">
                                    <a href="{{ route('meetinglist') }}" class="btn btn-warning">Semula</a>
                                </div>
                            </div>
                    
                        </form>
                    </div>
                </section>

                <section class="panel panel-default">
                    <div class="panel-heading">Senarai Mesyuarat</div>
                    <div class="panel-body table-responsive">
                        <table class="table" id="complaint">
                            <thead style="background-color: #62549a; color: #ffffff">
                            <tr>
                                <th width="4%">Bil</th>
                                <th width="12%">No. Permohonan</th>
                                <th>Nama Pemohon</th>
                                <th width="30%">Lokasi Mesyuarat</th>
                                <th width="12%">Tarikh/Masa</th>
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
                            $lokasi = '';
                            if($data->block != '')
                              $lokasi = "Blok ".$data->block;
                            if($data->level != '')
                              $lokasi .= ", ".$data->level;
                            if($data->zone != '')
                              $lokasi .= ", Zon ".$data->zone;
                            @endphp
                            <tr>
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
                                <td><strong>{{ $data->location }}</strong><br>{{ $data->sector_desc }}<br>{!! $data->department_desc.'<br>'.$lokasi !!}</td>
                                <td>{!! App\Helper\Utilities::meetingTime($data->id) !!}</td>
                                <td>{!! App\Helper\Utilities::getPelaksana($data->id) !!}</td>
                                <td>
                                @if($data->status_id == 1)
                                    <label class="label label-default">Permohonan Baru</label>
                                @elseif($data->status_id == 2)
                                    <label class="label label-danger">Dalam Tindakan</label>    
                                @elseif($data->status_id == 8)
                                    <label class="label label-success">Selesai</label>
                                @endif    
                                </td>
                                <td align="center">
                                    @if($data->status_id == 8) <!-- jika selesai -->
                                        <a href="{{ route('show_meeting', Crypt::encrypt($data->id)) }}" style="text-decoration: none;" title="Papar"><i class="icon-eye"></i></a>
                                    @else <!-- jika belum selesai -->    
                                        @if(Auth::user()->role_id == 2)
                                            <a href="{{ route('edit_meeting', Crypt::encrypt($data->id)) }}" style="text-decoration: none;" title="Kemaskini"><i class="icon-note"></i></a>
                                        @elseif(Auth::user()->role_id == 4)
                                            <a href="{{ route('show_meeting', Crypt::encrypt($data->id)) }}" style="text-decoration: none;" title="Papar"><i class="icon-note"></i></a>
                                        @endif
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