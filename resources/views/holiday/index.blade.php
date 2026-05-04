@extends('layout.mainlayout')

@section('content')
<style>
    i[class^='icon-'], i[class*=' icon-']
    {
        font-size: 20px;
    }
    i[class^='ti-'], i[class*=' ti-']
    {
        font-size: 20px;
    }
</style>
<script type="text/javascript">
$(document).ready(function(){
	var oTable = $('#categories').DataTable({
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
                    <div class="panel-body table-responsive">
                        <form class="form-horizontal" method="get">
                            @csrf
                            <div class="form-group">
                                <label for="search" class="col-sm-2 control-label">Carian</label>
                                <div class="col-lg-4">
                                    <input class="form-control" name="search" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="year" class="col-sm-2 control-label">Tahun</label>
                                <div class="col-lg-4">
                                    <select name="tahun" id="tahun" class="form-control">
                                    @php
                                    $start_years = 2021;
                                    $curr_years = date('Y');
                                    
                                    $years = range($curr_years, $start_years);
                                    @endphp
                                    @foreach($years as $year)
                                    <option value="{{ $year }}" @if($y == $year) selected @endif >{{ $year }}</option>  
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">&nbsp;</label>
                                <div class="col-sm-10 text-left">
                                    <input type="submit" class="btn btn-success" style="min-width:100px" value="Hantar">
                                    <a href="{{ route('holidays') }}" class="btn btn-warning">Semula</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>

                <section class="panel panel-default">
                	<div class="panel-heading">Senarai Cuti Umum</div>
                    <div class="panel-body table-responsive">
                        <table class="table convert-data-table table-striped" id="categories">
                            <thead style="background-color: #62549a; color: #ffffff">
                            <tr>
                                <th width="5%">Bil</th>
                                <th>Keterangan</th>
                                <th>Tarikh</th>
                                <th width="8%">Aktiviti</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php	
                            $bil = 1;
                            @endphp
                            @foreach($holidays as $data)	
                            <tr>
                            	<td align="right">{{ $bil++ }}.</td>
                            	<td>{{ $data->name }}</td>
                                <td>{{ $data->date }}</td>
                            	<td align="center">

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