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

	var oTable = $('#categories').DataTable({
        ordering: false,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Malay.json"
        },
		dom: 'tipr'
	});

    $('.editBtn').on('click', function(){
	   	var id = $(this).data('id');

        var url = '{{ route("getcategory", ":id") }}';
        url = url.replace(':id', id );

	   	$.ajax({
			url: url,
			type: 'get',
			dataType: 'json',
			success: function(response){

                //console.log(response);
                var categories = response['arr1'];
                var sections = response['arr2'];
                
                $('#category_desc').val(categories.category_desc);
                $('#id').val(categories.id);
                
                if(sections){
                    $(".section").empty();
                    $(".section").append('<option value="">Sila Pilih</option>');
                    $.each(sections, function(key, value) {
                        $(".section").append('<option value="'+ value.id +'">'+ value.section_desc +'</option>');
                    });

                    $('.section option[value="'+categories.section_id+'"]').prop('selected', true);
                }else{
                    $(".section").empty();
                }

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
        var url = '{{ route("delete_category") }}';

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
                                <label for="year" class="col-sm-2 control-label">Seksyen</label>
                                <div class="col-lg-4">
                                    <select name="section" class="form-control">
                                        <option value="">Sila Pilih</option>
                                        @foreach($sections as $row)
                                        <option value="{{ $row->id }}" {{ $sec == $row->id ? 'selected' : '' }} >{{ $row->section_desc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">&nbsp;</label>
                                <div class="col-sm-10 text-left">
                                    <input type="submit" class="btn btn-success" style="min-width:100px" value="Hantar">
                                    <a href="{{ route('category') }}" class="btn btn-warning">Semula</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>

                <section class="panel panel-default">
                	<div class="panel-heading">Senarai Kategori</div>
                    <div class="panel-body table-responsive">
                        <div class="order-short-info">
                            <a href="#addModal" data-toggle="modal" class="pull-right pull-left-xs btn btn-info">Tambah</a>
                        </div>
                        <table class="table convert-data-table table-striped" id="categories">
                            <thead style="background-color: #62549a; color: #ffffff">
                            <tr>
                                <th width="5%">Bil</th>
                                <th>Keterangan</th>
                                <th>Seksyen</th>
                                <th width="8%">Aktiviti</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php	
                            $bil = 1;
                            @endphp
                            @foreach($categories as $data)	
                            <tr>
                            	<td align="right">{{ $bil++ }}.</td>
                            	<td>{{ $data->category_desc }}</td>
                                <td>{{ $data->section_desc }}</td>
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
                        <h4 class="modal-title">Tambah Kategori</h4>
                    </div>
                    <div class="modal-body">

                        <form class="form-horizontal" method="post" action="{{ route('add_category') }}">
                            @csrf
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Keterangan Kategori</label>
                                <div class="col-lg-8">
                                    <input class="form-control @error('category_desc') is-invalid @enderror" name="category_desc" type="text" value="{{ old('category_desc') }}">
                                    @error('category_desc')
                                    <label id="name-error" class="error" for="category_desc">{{$message}}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Seksyen</label>
                                <div class="col-lg-6">
                                    <select name="section" class="form-control section">
                                        <option value="">Sila Pilih</option>
                                        @foreach($sections as $row)
                                        <option value="{{ $row->id }}" >{{ $row->section_desc }}</option>
                                        @endforeach
                                    </select>
                                    @error('section')
                                    <label id="name-error" class="error" for="section">{{$message}}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-4 control-label"></label>
                                <div class="col-lg-8">
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
                        <h4 class="modal-title">Kemaskini Kategori</h4>
                    </div>
                    <div class="modal-body">

                        <form class="form-horizontal" method="post" action="{{ route('update_category') }}">
                            @csrf
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Keterangan Kategori</label>
                                <div class="col-lg-8">
                                    <input class="form-control @error('category_desc') is-invalid @enderror" name="category_desc" id="category_desc" type="text" value="{{ old('category_desc') }}">
                                    @error('category_desc')
                                    <label id="name-error" class="error" for="category_desc">{{$message}}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Seksyen</label>
                                <div class="col-lg-6">
                                    <select name="section" class="form-control section">
                                    </select>
                                    @error('section')
                                    <label id="name-error" class="error" for="section">{{$message}}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-4 control-label"></label>
                                <div class="col-lg-8">
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