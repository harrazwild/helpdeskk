@extends('layout.mainlayout')

@section('content')
<style>
    h4
    {
        font-weight: 600px;
        font-size: 18pt;
    }
    .topline
    {
        border: 2px solid #62549A;
        margin-top: 0px;
        margin-bottom: 14px;
    }
    i[class^='icon-'], i[class*=' icon-']
    {
        font-size: 20px;
    }
    .label
    {
        font-size: 10pt;
    }
    .komen
    {
        border: 1px solid #ddd;
        padding: 20px;
    }
    p 
    {
        white-space: pre-wrap;
    }
    .well
    {
        white-space: pre-wrap;
        font-size: 10.5pt;
    }
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th
    {
        border-top: 0px;
    }
    .profile-tabs .tab-content
    {
        border-top: 2px solid #4aa9e9;
    }
    .activity-list .activity-item .well
    {
        margin-top: 0px;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        
        $('#status').change(function(){
            var id = $(this).val();

            if(id == 3){
                $('#officers').show();
            }else{
                $('#officers').hide();
            }
        });

        $('.modal').on('hidden.bs.modal', function(){
            $(this).find('form')[0].reset();
        });

        $('#summernote').summernote({
            height: 300,                 // set editor height
            minHeight: null,             // set minimum height of editor
            maxHeight: null,             // set maximum height of editor
            focus: true                  // set focus to editable area after initializing summernote
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Set semula kategori
        $("#category").change(function(e){
            
            e.preventDefault();

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

                var id = $("#id").val();
                var category = $("#category").val();
                var url = '{{ route("changeCategory") }}';

                $.ajax({
                    url:url,
                    method:'POST',
                    data:{
                            "_token": "{{ csrf_token() }}",
                            "id": id,
                            "category": category
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

                            toastr["success"]('Maklumat Berjaya Dikemaskini', 'BERJAYA');
                            setTimeout(function(){ window.location.href = "{{ route('complaintlist') }}"; }, 1000);
                        }
                    }

                });
              }
            });

        });

        // Set sub kategori
        $("#subcategory").change(function(e){

            e.preventDefault();

            var id = $("#id").val();
            var subcategory = $("#subcategory").val();
            var url = '{{ route("changeSubCategory") }}';

            $.ajax({
                url:url,
                method:'POST',
                data:{
                        "_token": "{{ csrf_token() }}",
                        "id": id,
                        "subcategory": subcategory
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

                        toastr["success"]('Maklumat Berjaya Dikemaskini', 'BERJAYA');
                    }
                }

            });
              
        });

        // Assign Pelaksana
        $("#staff").change(function(e){

            e.preventDefault();

            var id = $("#id").val();
            var staff = $("#staff").val();
            var url = '{{ route("assigntask") }}';

            $.ajax({
                url:url,
                method:'POST',
                data:{
                        "_token": "{{ csrf_token() }}",
                        "id": id,
                        "staff": staff
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

                        toastr["success"]('Maklumat Berjaya Dikemaskini', 'BERJAYA');
                        setTimeout(function(){ window.location.reload(); }, 1000);
                    }
                }

            });
              
        });

        // Assign Pelaksana
        $("#take").click(function(e){

            e.preventDefault();

            var id = $("#id").val();
            var staff = {{ Auth::user()->id }};
            var url = '{{ route("assigntask") }}';

            $.ajax({
                url:url,
                method:'POST',
                data:{
                        "_token": "{{ csrf_token() }}",
                        "id": id,
                        "staff": staff
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

                        toastr["success"]('Maklumat Berjaya Dikemaskini', 'BERJAYA');
                        setTimeout(function(){ window.location.reload(); }, 1000);
                    }
                }

            });
              
        });

        // button selesai
        $("#done").click(function(e){
            
            e.preventDefault();

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

                var id = $("#done").val();
                var url = '{{ route("complaintDone") }}';

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

                            toastr["success"]('Maklumat Berjaya Dikemaskini', 'BERJAYA');
                            setTimeout(function(){ window.location.reload(); }, 1000);
                        }
                    }

                });
              }
            });

        });

        // button selesai
        $("#delete").click(function(e){
            
            e.preventDefault();

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

                var id = $("#delete").val();
                var url = '{{ route("complaintDelete") }}';

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
                            setTimeout(function(){ window.location.href = "{{ route('complaintlist') }}"; }, 1000);
                        }
                    }

                });
              }
            });

        });

        // button kemaskini ulasan
        $('.editRemark').on('click', function(){
            var id = $(this).data('id');

            var url = '{{ route("getRemark", ":id") }}';
            url = url.replace(':id', id );

            $.ajax({
                url: url,
                type: 'get',
                dataType: 'json',
                success: function(response){
                    $('#remarks_id').val(response.id);
                    $('#remarks').val(response.remarks);
                    
                    $('#editModal').modal('show');      
                },
                error: function (response) {
                    console.log(response);
                }
            });
 
        });

        // button hapus ulasan
        $(".delRemark").click(function(e){
            
            e.preventDefault();

            var id = $(this).data('id');
            var url = '{{ route("delRemark") }}';

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
            <div class="col-lg-4" style="padding-right: 0px;">
                    <h4>NO ADUAN : #{{ $complaint->application_no }}</h4>
            </div>
            <div class="col-lg-8" style="padding-left: 0px">
            </div>
        </div>
        <hr class="topline">
        <div class="row">
            <div class="col-lg-4">
                
                <div class="panel panel-info">
                    <div class="panel-heading">Maklumat Pengadu</div>
                    <div class="panel-body">
                        <table width="100%" class="table noborder" style="margin-bottom: 0px">
                            <tr style="border-bottom: 1px solid #ddd">
                                <td width="5%"><i class="icon-user"></i></td>
                                <td colspan="3">{{ $complaint->name }}</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #ddd">
                                <td width="5%"><i class="icon-location-pin"></i></td>
                                <td colspan="3">{{ $complaint->location }}<br>{{ $complaint->sector_desc }}<br>{{ $complaint->department_desc }}</td>
                            </tr>
                            <tr>
                                <td width="5%"><i class="icon-phone"></i></td>
                                <td>{{ $complaint->telephone }} (Pejabat)<br>{{ $complaint->handphone }} (Bimbit)</td>
                                <td width="5%"><i class="icon-envelope"></i></td>
                                <td>{{ $complaint->email }}</td>
                            </tr>
                        </table>


                        
                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading">Maklumat Pelaksana</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="post" action="/">
                            <input type="hidden" id="id" value="{{ $complaint->id }}">
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Kategori</label>
                                <div class="col-sm-6">
                                    @if($complaint->status_id == 4)

                                        <p class="form-control-static" style="white-space: normal;">{!! App\Helper\Utilities::getCategory($complaint->category_id) !!}</p>

                                    @else

                                        @if(Auth::user()->role_id == 2)
                                        <select class="form-control" id="category">
                                            <option value="">Sila Pilih</option>
                                            @foreach($categories as $row)
                                            <option value="{{ $row->id }}" {{ $complaint->category_id == $row->id ? 'selected' : '' }} >{{ $row->category_desc }}</option>
                                            @endforeach
                                        </select>
                                        @else
                                        <p class="form-control-static" style="white-space: normal;">{!! App\Helper\Utilities::getCategory($complaint->category_id) !!}</p>
                                        @endif

                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Sub Kategori</label>
                                <div class="col-sm-6">
                                    @if($complaint->status_id == 4)

                                        <p class="form-control-static" style="white-space: normal;">{{ (!empty($complaint->subcategory_id)) ? App\Helper\Utilities::getSubCategory($complaint->subcategory_id) : "-"  }}</p>
                                        
                                    @else

                                        <select class="form-control" id="subcategory">
                                            <option value="">Sila Pilih</option>
                                            @foreach($subcategories as $row)
                                            <option value="{{ $row->id }}" {{ $complaint->subcategory_id == $row->id ? 'selected' : '' }} >{{ $row->subcategory_desc }}</option>
                                            @endforeach
                                        </select>

                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Pelaksana</label>
                                <div class="col-sm-8">
                                    @if($complaint->status_id == 4) <!-- Jika status selesai -->

                                        <p class="form-control-static" style="white-space: normal;">{{ (!empty($tasks->user_id)) ? App\Helper\Utilities::getStaffName($tasks->user_id) : "-"  }}</p>

                                    @else <!-- Jika status aduan baru atau dlm tindakan -->

                                        @if(Auth::user()->role_id == 2) <!-- jika pentadbir -->
                                        <select class="form-control" id="staff">
                                            <option value="">Sila Pilih</option>
                                            @foreach($staffs as $row)
                                            <option value="{{ $row->id }}" @if(!empty($tasks->user_id) && $tasks->user_id == $row->id) selected @endif >{{ $row->name }}</option>
                                            @endforeach
                                        </select>
                                        @else <!-- jika bukan pentadbir -->

                                            
                                            <p class="form-control-static" style="white-space: normal;">{!! (!empty($tasks->user_id)) ? App\Helper\Utilities::getStaffName($tasks->user_id)."<br>" : ""  !!}

                                                @if(!empty($tasks->user_id) && $tasks->user_id != Auth::user()->id)<button class="btn btn-danger btn-xs" id="take">Ambil Tindakan</button>@endif
                                            </p>
                                            
                                        
                                        @endif <!-- !end pentadbir -->

                                    @endif <!-- !end status -->
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                        <div class="col-md-6 text-left"><a href="{{ route('complaintlist') }}" class="btn btn-warning"><i class="ti ti-control-backward"></i> Kembali</a></div>
                        <div class="col-md-6 text-right">
                        <div class="btn-group">
                            <!-- <button class="btn btn-default btn-success" id="done" value="{{ $complaint->id }}" @if($complaint->status_id == 4) disabled="" @endif type="button" data-toggle="tooltip" data-placement="bottom" data-original-title="Aduan Selesai"><i class="icon-check"></i></button>
                             <button class="btn btn-default btn-info" id="print" value="{{ $complaint->id }}" type="button" data-toggle="tooltip" data-placement="bottom" data-original-title="Cetak"><i class="icon-printer"></i></button> -->

                            @if($tasks)

                                @if($tasks->user_id)
                                    <a target="_blank" href="https://wa.me/6{{ App\Helper\Utilities::phonenumber($tasks->user_id) }}/?text=*Sistem Helpdesk* :%0A{{ $complaint->name }}%0A{{ $complaint->location }}%0A{{ $complaint->sector_desc }}%0A{{ $complaint->department_desc }}%0A%0A{{ $complaint->remarks }}" class="btn btn-default btn-success" value="{{ $complaint->id }}" type="button" data-toggle="tooltip" data-placement="bottom" data-original-title="Whatsapp"><i class="icon-bubble"></i></a>
                                @else
                                    <a href="#" class="btn btn-default btn-success" disabled type="button" data-toggle="tooltip" data-placement="bottom" data-original-title="Whatsapp"><i class="icon-bubble"></i></a>
                                @endif

                            @else
                                <a href="#" class="btn btn-default btn-success" disabled type="button" data-toggle="tooltip" data-placement="bottom" data-original-title="Whatsapp"><i class="icon-bubble"></i></a>
                            @endif
                            
                            @if($tasks)

                                @if($tasks->user_id)
                                    <a target="_blank" href="" class="btn btn-default btn-warning" value="{{ $complaint->id }}" type="button" data-toggle="tooltip" data-placement="bottom" data-original-title="Email"><i class="icon-envelope-open"></i></a>
                                @else
                                    <a href="#" class="btn btn-default btn-warning" disabled type="button" data-toggle="tooltip" data-placement="bottom" data-original-title="Email"><i class="icon-envelope-open"></i></a>
                                @endif

                            @else
                                <a href="#" class="btn btn-default btn-warning" disabled type="button" data-toggle="tooltip" data-placement="bottom" data-original-title="Email"><i class="icon-envelope-open"></i></a>
                            @endif

                            <button class="btn btn-default btn-danger" id="delete" value="{{ $complaint->id }}" type="button" data-toggle="tooltip" data-placement="bottom" data-original-title="Hapus Aduan"><i class=" icon-trash"></i></button>
                        </div>
                        </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-8">

                <div class="panel panel-info">
                    <div class="panel-body">
                        <div class="profile-tabs">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4>MAKLUMAT ADUAN</h4>
                                </div>
                                <!-- <div class="col-md-4 text-right" style="margin-top: 10px">
                                    <strong>Status : </strong><span class="label @if($complaint->status_id == 1) label-warning @elseif($complaint->status_id == 2) label-info @elseif($complaint->status_id == 4) label-success @endif">{{ $complaint->status_desc }}</span>
                                </div> -->
                            </div>
                            <div class="tab-content">
                                <div id="tab1" class="tab-pane fade in active">
                                    <div class="activity-list">
                                        
                                        <!-- ulasan -->                
                                        @if($complaint->status_id != 4)
                                        <div class="activity-item" style="margin-bottom: 40px;">
                                            <form method="post" action="{{ route('taskremarks') }}" autocomplete="off" class="form-horizontal">
                                                @csrf
                                                <input type="hidden" name="complaint_id" value="{{ $complaint->id }}">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="col-lg-3 control-label">Status</label>
                                                            <div class="col-lg-9">
                                                                <select class="form-control" name="status" id="status">
                                                                    <option value="">Status Pilih</option>
                                                                    @foreach($status as $row)
                                                                    <option value="{{ $row->id }}" @if(!empty($complaint->status_id) && $complaint->status_id == $row->id) selected @endif >{{ $row->status_desc }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6" id="officers" style="display: none;">
                                                        <div class="form-group">
                                                            <label class="col-lg-3 control-label">Pegawai</label>
                                                            <div class="col-lg-9">
                                                                <select class="form-control" name="officers">
                                                                    <option value="">Status Pilih</option>
                                                                    @foreach($officers as $row)
                                                                    <option value="{{ $row->id }}" @if(!empty($complaint->officer_id) && $complaint->officer_id == $row->id) selected @endif >{{ $row->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-lg-12">
                                                        <textarea class="form-control" rows="5" name="remarks"></textarea>
                                                    </div>
                                                </div>
                                                <input type="submit" class="btn btn-success btn-sm" style="min-width:100px" value="Simpan">
                                            </form>
                                        </div>
                                        @endif
                                        <!-- end ulasan --> 

                                        <!-- looping komen pelaksana dan pentadbir -->
                                        @foreach($remarks as $data)
                                        <div class="activity-item">
                                            <div class="media-body ">
                                                <strong>{{ $data->name }}</strong> <small class="text-muted">{{ Date('d/m/Y h:i A', strtotime($data->created_at)) }}</small>
                                                @if(Auth::user()->id == $data->user_id && $complaint->status_id != 4)
                                                <div class="pull-right" style="padding-right: 10px">
                                                    <a href="#" style="text-decoration: none;" class="editRemark" data-id="{{ $data->id }}" title="Kemaskini"><i class="fa fa-pencil" style=" font-size: 16px;"></i></a>
                                                    <a href="#" style="text-decoration: none;" class="delRemark" data-id="{{ $data->id }}" title="Hapus"><i class="fa fa-trash" style=" font-size: 16px;"></i></a>
                                                </div>
                                                @endif
                                                <div class="well">{{ $data->remarks }}</div>
                                            </div>
                                        </div>
                                        @endforeach
                                        <!-- end looping komen pelaksana dan pentadbir -->

                                        <div class="activity-item">
                                            <div class="media-body ">
                                                <strong>{{ $complaint->name }}</strong> <small class="text-muted">{{ Date('d/m/Y h:i A', strtotime($complaint->date_open)) }}</small>
                                                <div class="well">{{ $complaint->remarks }}@if(!empty($complaint->attachment))<br><hr style="margin-bottom: 5px; border-top: 3px solid #eee;"><a href="{{ asset('uploads/'.$complaint->attachment) }}" target="_blank" style="text-decoration: none;" title="Muatturun"><i class="fa fa-download" style="color: #62549a; font-size: 18px"></i> <small style="color: #62549a">{{ $complaint->attachment }}</small></a>@endif</div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!---------- edit modal form ---------->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="editModal" class="modal fade" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                        <h4 class="modal-title">Kemaskini Ulasan</h4>
                    </div>
                    <div class="modal-body">

                        <form class="form-horizontal" method="post" action="{{ route('updRemark') }}">
                            @csrf
                            <div class="form-group">
                                <label class="col-lg-2 control-label">Ulasan</label>
                                <div class="col-lg-10">
                                    <textarea class="form-control @error('remarks') is-invalid @enderror" rows="5" name="remarks" id="remarks"></textarea>
                                    @error('remarks')
                                    <label id="name-error" class="error" for="remarks">{{$message}}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label"></label>
                                <div class="col-lg-10">
                                    <input type="hidden" name="remarks_id" id="remarks_id">
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