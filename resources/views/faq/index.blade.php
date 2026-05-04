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

	var oTable = $('#faq').DataTable({
        ordering: false,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Malay.json"
        },
		dom: 'tipr'
	});

    $('#search').on('keyup', function() {
        oTable.search( this.value ).draw();
    });

    $('.editBtn').on('click', function(){
	   	var id = $(this).data('id');

        var url = '{{ route("getfaq", ":id") }}';
        url = url.replace(':id', id );

	   	$.ajax({
			url: url,
			type: 'get',
			dataType: 'json',
			success: function(response){

                console.log(response);

                //$('#column option[value="'+response.column+'"]').prop('selected', true);  
                $('#question').val(response.question);
                $('#answer').val(response.answer);
                $('#show').val(response.show);
                $('#id').val(response.id);

                if(response.show == 1){
                  //$("#active").attr('checked', 'checked');
                  //alert(response.active);
                  $('.checkbox').attr('checked', true);
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
        var url = '{{ route("delete_faq") }}';

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
                        <form class="form-horizontal" method="get" action="#">
                            @csrf
                            <div class="form-group">
                                <label for="search" class="col-sm-2 control-label">Carian</label>
                                <div class="col-lg-4">
                                    <input class="form-control" id="search" type="text">
                                </div>
<!--                                 <div class="col-lg-2">
                                    <button type="button" id="filter" class="btn btn-info">Cari</button>
                                    <button type="button" id="reset" class="btn btn-warning">Semula</button>
                                </div> -->
                            </div>
                        </form>
                    </div>
                </section>

                <section class="panel panel-default">
                	<div class="panel-heading">Senarai Soalan Lazim</div>
                    <div class="panel-body table-responsive">
                        <div class="order-short-info">
                            <a href="#addModal" data-toggle="modal" class="pull-right pull-left-xs btn btn-info">Tambah</a>
                        </div>
                        <table class="table convert-data-table table-striped" id="faq">
                            <thead style="background-color: #62549a; color: #ffffff">
                            <tr>
                                <th width="5%">Bil</th>
                                <th>Soalan</th>
                                <th>Jawapan</th>
                                {{-- <th>Lajur</th> --}}
                                <th>Papar</th>
                                <th width="8%">Aktiviti</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php	
                            $bil = 1;
                            @endphp
                            @foreach($faq as $data)	
                            <tr>
                            	<td align="right">{{ $bil++ }}.</td>
                            	<td>{{ $data->question }}</td>
                                <td>{{ $data->answer }}</td>
                                {{-- <td> --}}
                                {{-- @php
                                if($data->column == 1){
                                  echo "Kiri";
                                }elseif($data->column == 2){
                                  echo "Tengah";
                                }elseif($data->column == 3){
                                  echo "Kanan";
                                }
                                @endphp --}}    
                                {{-- </td> --}}
                                <td>
                                @php
                                if($data->show == 1){
                                  echo "Ya";
                                }elseif($data->show == 0){
                                  echo "Tidak";
                                }
                                @endphp 
                                </td>
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
                        <h4 class="modal-title">Tambah Soalan Lazim</h4>
                    </div>
                    <div class="modal-body">

                        <form class="form-horizontal" method="post" action="{{ route('add_faq') }}">
                            @csrf
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Soalan</label>
                                <div class="col-lg-8">
                                    <textarea rows="2" class="form-control @error('question') is-invalid @enderror" name="question">{{ old('question') }}</textarea>
                                    @error('question')
                                    <label id="name-error" class="error" for="question">{{$message}}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Jawapan</label>
                                <div class="col-lg-8">
                                    <textarea rows="4" class="form-control @error('answer') is-invalid @enderror" name="answer">{{ old('answer') }}</textarea>
                                    @error('answer')
                                    <label id="name-error" class="error" for="answer">{{$message}}</label>
                                    @enderror
                                </div>
                            </div>
                            {{-- <div class="form-group">
                                <label class="col-lg-4 control-label">Jalur</label>
                                <div class="col-lg-4">
                                    <select name="column" class="form-control column">
                                        <option value="">Sila Pilih</option>
                                        <option value="1">Kiri</option>
                                        <option value="2">Tengah</option>
                                        <option value="3">Kanan</option>
                                    </select>
                                </div>
                            </div> --}}
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Papar</label>
                                <div class="col-lg-8">
                                  <label class="checkbox-inline">
                                    <input id="show" class="checkbox" name="show" value="1" type="checkbox"> Ya
                                  </label>    
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
                        <h4 class="modal-title">Kemaskini Soalan Lazim</h4>
                    </div>
                    <div class="modal-body">

                        <form class="form-horizontal" method="post" action="{{ route('update_faq') }}">
                            @csrf
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Soalan</label>
                                <div class="col-lg-8">
                                    <textarea rows="2" class="form-control @error('question') is-invalid @enderror" id="question" name="question">{{ old('question') }}</textarea>
                                    @error('question')
                                    <label id="name-error" class="error" for="question">{{$message}}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Jawapan</label>
                                <div class="col-lg-8">
                                    <textarea rows="4" class="form-control @error('answer') is-invalid @enderror" id="answer" name="answer">{{ old('answer') }}</textarea>
                                    @error('answer')
                                    <label id="name-error" class="error" for="answer">{{$message}}</label>
                                    @enderror
                                </div>
                            </div>
                            {{-- <div class="form-group">
                                <label class="col-lg-4 control-label">Jalur</label>
                                <div class="col-lg-4">
                                    <select name="column" id="column" class="form-control column">
                                        <option value="">Sila Pilih</option>
                                        <option value="1">Kiri</option>
                                        <option value="2">Tengah</option>
                                        <option value="3">Kanan</option>
                                    </select>
                                    @error('column')
                                    <label id="name-error" class="error" for="column">{{$message}}</label>
                                    @enderror
                                </div>
                            </div> --}}
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Papar</label>
                                <div class="col-lg-8">
                                  <label class="checkbox-inline">
                                    <input id="show" class="checkbox" name="show" value="1" type="checkbox"> Ya
                                  </label>    
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