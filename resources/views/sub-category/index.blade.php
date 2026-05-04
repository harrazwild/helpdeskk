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
    
    $('.modal').on('hidden.bs.modal', function(){
	    $(this).find('form')[0].reset();
	});

	var oTable = $('#subcategories').DataTable({
        ordering: false,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Malay.json"
        },
		dom: 'tipr'
	});

    $('.editBtn').on('click', function(){
	   	var id = $(this).data('id');

        var url = '{{ route("getSubCategory", ":id") }}';
        url = url.replace(':id', id );

	   	$.ajax({
			url: url,
			type: 'get',
			dataType: 'json',
			success: function(response){
                $('#category option[value="'+response.category_id+'"]').prop('selected', true);  
                $('#subcategory_desc').val(response.subcategory_desc);
                $('#id').val(response.id);

                $('#editModal').modal('show');  	
			},
			error: function (response) {
                console.log(response);
            }
		});

	   $('#editModal').modal('show');  
	});

	$(".delBtn").on('click', function(e){

		e.preventDefault();
		
		var id = $(this).data('id');
        var url = '{{ route("delete_subcategory") }}';

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
                method:'POST',
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

                        toastr["success"]('Maklumat Berjaya Dihapuskan', 'BERJAYA');
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
                                    <input class="form-control" name="search" type="text" value="{{ $search }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="year" class="col-sm-2 control-label">Kategori</label>
                                <div class="col-lg-4">
                                    <select name="category" name="category" class="form-control">
                                        <option value="">Sila Pilih</option>
                                        @foreach($categories as $row)
                                        <option value="{{ $row->id }}" {{ $cat == $row->id ? 'selected' : '' }} >{{ $row->category_desc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">&nbsp;</label>
                                <div class="col-sm-10 text-left">
                                    <input type="submit" class="btn btn-success" style="min-width:100px" value="Hantar">
                                    <a href="{{ route('sub-category') }}" class="btn btn-warning">Semula</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>

                <section class="panel panel-default">
                	<div class="panel-heading">Senarai Sub Kategori</div>
                    <div class="panel-body table-responsive">
                        <div class="order-short-info">
                            <a href="#addModal" data-toggle="modal" class="pull-right pull-left-xs btn btn-info">Tambah</a>
                        </div>
                        <table class="table convert-data-table table-striped" id="subcategories">
                            <thead style="background-color: #62549a; color: #ffffff">
                            <tr>
                                <th width="5%">Bil</th>
                                <th>Keterangan</th>
                                <th width="30%">Kategori</th>
                                <th width="8%">Aktiviti</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php	
                            $bil = 1;
                            @endphp
                            @foreach($subcategories as $data)	
                            <tr>
                            	<td align="right">{{ $bil++ }}.</td>
                            	<td>{{ $data->subcategory_desc }}</td>
                            	<td>{{ $data->category_desc }}</td>
                            	<td align="center">
                            		<a href="#" class="editBtn" data-id="{{ $data->id }}" style="text-decoration: none;" title="Kemaskini"><i class="ti-pencil"></i></a>
                            		<a href="#" class="delBtn" data-id="{{ $data->id }}" style="text-decoration: none;" title="Hapus"><i class="ti-close"></i></a>
                            	</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </section>
            </div>

        </div>

        <!---------- add modal form ---------->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="addModal" class="modal fade" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                        <h4 class="modal-title">Tambah Sub Kategori</h4>
                    </div>
                    <div class="modal-body">

                        <form class="form-horizontal" method="post" action="{{ route('add_subcategory') }}">
                            @csrf
                            <div class="form-group">
                                <label class="col-lg-5 control-label">Kategori</label>
                                <div class="col-lg-4">
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Sila Pilih</option>
                                        @foreach($categories as $row)
                                        <option value="{{ $row->id }}" >{{ $row->category_desc }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                    <label id="name-error" class="error" for="category">{{$message}}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-5 control-label">Keterangan Sub Kategori</label>
                                <div class="col-lg-7">
                                    <input class="form-control @error('subcategory_desc') is-invalid @enderror" name="subcategory_desc" type="text" value="{{ old('subcategory_desc') }}">
                                    @error('subcategory_desc')
                                    <label id="name-error" class="error" for="subcategory_desc">{{$message}}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-5 control-label"></label>
                                <div class="col-lg-7">
                                    <input type="submit" class="btn btn-success" name="submit" value="Simpan">
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!---------- !end add modal form ---------->

        <!---------- edit modal form ---------->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="editModal" class="modal fade" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                        <h4 class="modal-title">Kemaskini Sub Kategori</h4>
                    </div>
                    <div class="modal-body">

                        <form class="form-horizontal" method="post" action="{{ route('update_subcategory') }}">
                            @csrf
                            <div class="form-group">
                                <label class="col-lg-5 control-label">Kategori</label>
                                <div class="col-lg-4">
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Sila Pilih</option>
                                        @foreach($categories as $row)
                                        <option value="{{ $row->id }}" >{{ $row->category_desc }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                    <label id="name-error" class="error" for="category">{{$message}}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-5 control-label">Keterangan Sub Kategori</label>
                                <div class="col-lg-7">
                                    <input class="form-control @error('subcategory_desc') is-invalid @enderror" id="subcategory_desc" name="subcategory_desc" type="text" value="{{ old('subcategory_desc') }}">
                                    @error('subcategory_desc')
                                    <label id="name-error" class="error" for="subcategory_desc">{{$message}}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-5 control-label"></label>
                                <div class="col-lg-7">
                                    <input type="hidden" name="id" id="id">
                                    <input type="submit" class="btn btn-success" name="submit" value="Simpan">
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!---------- !end edit modal form ---------->

    </div>
</div>
@endsection