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

    $('.sector').on('change',function(e) {
        var id = $(this).val();

        if(id){
          var url = '{{ route("getDepartments", ":id") }}';
          url = url.replace(':id', id );
          $.ajax({
            type:"GET",
            url: url,
            success:function(res){        
            //console.log(res);
              if(res){
                $(".department").empty();
                $(".department").append('<option value="">Sila Pilih</option>');
                $.each(res, function(key, value) {
                  $(".department").append('<option value="'+ value.department_code +'">'+ value.department_desc +'</option>');
                });

                $('.department option[value="{{ $department }}"]').prop('selected', true);

              }else{
                $(".department").empty();
                $(".department").append('<option value="">Sila Pilih</option>');
              }
            }
          });
        }else{
          $(".department").empty();
          $(".department").append('<option value="">Sila Pilih Sektor</option>');
        }
    });

	$(".resetBtn").on('click', function(e){

		e.preventDefault();
		

		var id = $(this).data('id');
        var url = '{{ route("user_reset") }}';

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

            $.ajax({
                url:url,
                method:'PUT',
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

                        toastr["success"]('Katalaluan Berjaya Dikemaskini', 'BERJAYA');
                        window.location.reload();
                    }
                }

            });
          }
        });

    });

    $(".delBtn").on('click', function(e){

        e.preventDefault();
        
        var id = $(this).data('id');
        var url = '{{ route("user_del") }}';

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

            $.ajax({
                url:url,
                method:'PUT',
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

                        toastr["success"]('Pengguna Berjaya Dinyah Aktifkan', 'BERJAYA');
                        window.location.reload();
                    }
                }

            });
          }
        });

    });

    $(".activeBtn").on('click', function(e){

        e.preventDefault();
        
        var id = $(this).data('id');
        var url = '{{ route("activate_user") }}';

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

            $.ajax({
                url:url,
                method:'PUT',
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

                        toastr["success"]('Pengguna Berjaya Diaktifkan', 'BERJAYA');
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
                    <div class="panel-body table-responsive">
                        <form class="form-horizontal" method="get">
                            @csrf
                            <div class="form-group">
                                <label for="search" class="col-sm-2 control-label">Carian</label>
                                <div class="col-lg-4">
                                    <input class="form-control" id="search" name="search" type="text" value="{{ $search }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="year" class="col-sm-2 control-label">Sektor</label>
                                <div class="col-lg-4">
                                    <select name="sector" id="sector" class="form-control sector">
                                        <option value="">Sila Pilih</option>
                                        @foreach($sectors as $row)
                                        <option value="{{ $row->sector_code }}" {{ $sec == $row->sector_code ? 'selected' : '' }} >{{ $row->sector_desc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="year" class="col-sm-2 control-label">Bahagian</label>
                                <div class="col-lg-4">
                                    <select name="department" id="department" class="form-control department">
                                        <option value="">Sila Pilih Sektor</option>
                                        @foreach($departments as $row)
                                        <option value="{{ $row->department_code }}" {{ $department == $row->department_code ? 'selected' : '' }} >{{ $row->department_desc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="year" class="col-sm-2 control-label">Peranan</label>
                                <div class="col-lg-2">
                                    <select name="role" id="role" class="form-control">
                                        <option value="">Sila Pilih</option>
                                        @foreach($roles as $row)
                                        <option value="{{ $row->id }}" {{ $role == $row->id ? 'selected' : '' }} >{{ $row->role_desc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">&nbsp;</label>
                                <div class="col-sm-10 text-left">
                                    <input type="submit" class="btn btn-success" style="min-width:100px" value="Hantar">
                                    <a href="{{ route('user') }}" class="btn btn-warning">Semula</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>

                <section class="panel panel-default">
                	<div class="panel-heading">Senarai Pengguna</div>
                    <div class="panel-body table-responsive">
                        <div class="order-short-info">
                            <a href="{{ route('new_user') }}" class="pull-right pull-left-xs btn btn-info">Tambah</a>
                        </div>
                        <table class="table convert-data-table table-striped" id="users">
                            <thead style="background-color: #62549a; color: #ffffff">
                            <tr>
                                <th width="5%">Bil</th>
                                <th>Nama</th>
                                <th>Sektor/Bahagian</th>
                                <th>Peranan</th>
                                <th width="8%">Status</th>
                                <th width="12%">Aktiviti</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php	
                            $bil = 1;
                            @endphp
                            @foreach($users as $data)
                            <tr>
                            	<td align="right">{{ $bil++ }}.</td>
                            	<td>{{ $data->name }}<br><span class="label label-info">{{ $data->position_desc }}</span></td>
                                <td>
                                    {!! $data->sector_desc !!}
                                    @if(isset($data->department_desc))
                                    {!! '<br>'.$data->department_desc !!}
                                    @endif    
                                </td>
                                <td>{{ $data->role_desc }}</td>
                                <td>
                                @if($data->active == 1)
                                Aktif
                                @elseif($data->active == 0)
                                Tidak Aktif
                                @endif    
                                </td>
                            	<td align="center">
                            		<a href="{{ route('show_user', Crypt::encrypt($data->id)) }}" style="text-decoration: none;" title="Papar"><i class="ti-eye"></i></a>
                                    <a href="{{ route('edit_user', Crypt::encrypt($data->id)) }}" style="text-decoration: none;" title="Kemaskini"><i class="ti-pencil"></i></a>
                                    <a href="#" class="resetBtn" data-id="{{ $data->id }}" style="text-decoration: none;" title="Tetapan Semula Katalaluan"><i class="ti-lock"></i></a>
                                    @if($data->active == 0)
                                    <a href="#" class="activeBtn" data-id="{{ $data->id }}" style="text-decoration: none;" title="Aktifkan Pengguna"><i class="icon-user-following"></i></a>
                            		@endif
                                    <a href="#" class="delBtn" data-id="{{ $data->id }}" style="text-decoration: none;" title="Nyah Aktif Pengguna"><i class="ti-close"></i></a>
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