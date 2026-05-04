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
    
	var oTable = $('#users').DataTable({
        ordering: false,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Malay.json"
        },
		dom: 'tipr'
	});

    $('#search').on('keyup', function() {
        oTable.search( this.value ).draw();
    });

    $('#section').on('change', function(){
       oTable.columns(2).search( this.value ).draw();  
    });

    $('#role').on('change', function(){
       oTable.columns(3).search( this.value ).draw();  
    });

});
</script>
<div class="ui-content-body">  
    <div class="ui-container">

        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <div class="panel-body table-responsive">
                        <form class="form-horizontal" method="get" action="#">
                            <div class="form-group">
                                <label for="search" class="col-sm-2 control-label">Carian</label>
                                <div class="col-lg-4">
                                    <input class="form-control" id="search" type="text">
                                </div>
                            </div>
                        </form>
                    </div>
                </section>

                <section class="panel panel-default">
                	<div class="panel-heading">Senarai Pengguna</div>
                    <div class="panel-body table-responsive">
                        <table class="table convert-data-table table-striped" id="users">
                            <thead style="background-color: #62549a; color: #ffffff">
                            <tr>
                                <th width="5%">Bil</th>
                                <th>Nama</th>
                                <th>Jawatan</th>
                                <th width="10%">Dalam Tindakan</th>
                                <th width="10%">Selesai</th>
                                <th width="10%">Jumlah</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php	
                            $bil = 1;
                            @endphp
                            @foreach($users as $data)
                            <tr>
                            	<td align="right">{{ $bil++ }}.</td>
                            	<td>{{ $data->name }}<br><span class="label label-info">{{ $data->role_desc }}</span></td>
                                <td>{{ $data->position_desc }}</td>
                                <td align="center"><strong>{!! App\Helper\Utilities::countTask($data->id, 2) !!}</strong></td>
                                <td align="center"><strong>{!! App\Helper\Utilities::countTask($data->id, 4) !!}</strong></td>
                                <td align="center"><strong>{!! App\Helper\Utilities::totalTask($data->id) !!}</strong></td>
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