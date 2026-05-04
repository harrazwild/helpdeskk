@extends('layout.mainlayout')

@section('content')
<style>
.Box-aside
{
    width: 500px;
    border-right: none;
}
.Box-innerContent
{
    border-left: 1px solid rgba(0, 0, 0, 0.1);
    min-height: 700px;
}
small, .small
{
    font-size: 80%;
}
.h4, .h5, .h6, h4, h5, h6
{
    margin-top: 0px;
    margin-bottom: 0px;
}
.Box-wrapper
{
    border: 1px solid #4aa9e9;
    background-color: #fff;
}
.task-sum-list > li
{
    border-bottom: none;
}
.ulas
{
    border-top: 1px solid #e5e5e5;
}
.noborder>tbody>tr>td, .noborder>tbody>tr>th, .noborder>tfoot>tr>td, .noborder>tfoot>tr>th, .noborder>thead>tr>td, .noborder>thead>tr>th
{
    border-top: none;
}
.attachment
{
    padding: 20px;
    border: solid 1px #ddd;
}
</style>
<script type="text/javascript">
$(document).ready(function(){

    $('.details').select2({
        placeholder: "Sila Pilih"
    });

    @if($complaint->status_id == 3)
        $('#officers').show();
    @else
        $('#officers').hide();
    @endif

    @if(isset($details->id) || isset($complaint->detail_id))
        $('#details').show();
    @endif

    $('.status').change(function(e){
        
        var status = $(this).val();
        e.preventDefault();

        if(status == 3){
            $('#officers').show();
        }else if(status == 4){

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
                var url = '{{ route("changeStatus") }}';

                $.ajax({
                    url:url,
                    method:'POST',
                    data:{
                            "_token": "{{ csrf_token() }}",
                            "id": id,
                            "status": status
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
              }else{
                $(".status").val('2');
                $('#officers').hide();
              }
            });

        }else{
            $('#officers').hide();

            var id = $("#id").val();
            //var status = $(this).val();
            var url = '{{ route("changeStatus") }}';

            $.ajax({
                url:url,
                method:'POST',
                data:{
                        "_token": "{{ csrf_token() }}",
                        "id": id,
                        "status": status
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

    $('.officer').change(function(e){

        e.preventDefault();

        var id = $("#id").val();
        var officer = $(".officer").val();
        var url = '{{ route("setOfficer") }}';

        $.ajax({
            url:url,
            method:'POST',
            data:{
                    "_token": "{{ csrf_token() }}",
                    "id": id,
                    "officer": officer
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
                    //setTimeout(function(){ window.location.reload(); }, 1000);
                }
            }

        });

    });

    $('.modal').on('hidden.bs.modal', function(){
        $(this).find('form')[0].reset();
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

                    if(response == 0){    
                        setTimeout(function(){ window.location.href = "{{ route('complaintlist') }}"; }, 1000);
                    }else if(response == 1){
                        setTimeout(function(){ window.location.reload(); }, 1000);
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

        if(subcategory){
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

                    var url = '{{ route("getDetails", ":id") }}';
                    url = url.replace(':id', subcategory );

                    $.ajax({
                      type:"GET",
                      url: url,
                      success:function(res){        
                          if(res && res.length != 0){
                            $(".details").empty();
                            $(".details").append('<option value="">Sila Pilih</option>');
                            $.each(res,function(key,value){
                              $(".details").append('<option value="'+value.id+'">'+value.detail_desc+'</option>');
                            });

                            $('#details').show();
                          }else{
                            $('#details').hide();
                            $(".details").empty();
                          }
                      }
                    });
                }
            }

        });
        }else{
           $(".details").empty();
           //$(".details").append('<option value="">Sila Pilih</option>');
           $("#details").hide(); 
        }
          
    });

    // Set Perincian
    $(".details").change(function(e){

        e.preventDefault();

        var id = $("#id").val();
        var detail_id = $(this).val();
        var url = '{{ route("changeDetail") }}';

        $.ajax({
            url:url,
            method:'POST',
            data:{
                    "_token": "{{ csrf_token() }}",
                    "id": id,
                    "detail_id": detail_id
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
                    //setTimeout(function(){ window.location.reload(); }, 1000);
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

    // Assign Pelaksana
    $("#take").click(function(e){
        e.preventDefault();

        var id = $("#id").val();
        var staff = '{{ Auth::user()->id }}';
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

    // button kemaskini ulasan
    $('.email').on('click', function(){
        var id = $(this).data('id');
        var url = '{{ route("send_emel") }}';
        //url = url.replace(':id', id );

        $.ajax({
            url: url,
            type: 'post',
            data:{
                    "_token": "{{ csrf_token() }}",
                    "id": id,
                },
            success: function(response){
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
                                        }

                if(response == 1){
                    toastr["success"]('Emel Berjaya Dihantar', 'BERJAYA');
                }else{
                    toastr["error"]('Emel Tidak Berjaya Dihantar', 'GAGAL');
                }
                //console.log(response);
            },
            error: function (response) {
                //toastr["error"]('Emel Tidak Berjaya Dihantar', 'GAGAL');
                console.log(response);
            }
        });

    });
     
});
</script>
<div class="ui-content-body">
    <div class="ui-container">
        <div class="row">
            <div class="col-md-12">

                <h3>#{{ $complaint->application_no }}</h3>

                <div class="Box-wrapper">
                    <div class="Box-aside">
                        <div class="Box niceScroll todo-aside">

                            <div class="Box__body">

                                <div class="panel-body aside-border">
                                    <h5 style="color: #0088CC">Maklumat Pengadu</h5>
                                </div>

                                
                                <ul class="list-unstyled">
                                    <li class="pengadu">
                                       <table width="100%" class="table noborder">
                                            <tr>
                                                <td style="padding-left: 20px" width="5%"><i class="icon-user"></i></td>
                                                <td>{{ $complaint->name }}<br>{{ $complaint->ic_number }}</td>
                                            </tr>
                                            <tr style="border-top: 1px solid #e5e5e5;">
                                                @php
                                                $lokasi = '';
                                                if($complaint->block != '')
                                                  $lokasi = "Blok ".$complaint->block;
                                                if($complaint->level != '')
                                                  $lokasi .= ", ".$complaint->level;
                                                if($complaint->zone != '')
                                                  $lokasi .= ", Zon ".$complaint->zone;
                                                @endphp
                                                <td style="padding-left: 20px"><i class="icon-location-pin"></i></td>
                                                <td>{{ $complaint->sector_desc }}<br>{{ $complaint->department_desc }}<br>{{ $lokasi }}<br>{{ $complaint->location }}</td>
                                            </tr>
                                            <tr style="border-top: 1px solid #e5e5e5;">
                                                <td style="padding-left: 20px"><i class="icon-phone"></i></td>
                                                <td>{{ $complaint->telephone }} (Pejabat)<br>{{ $complaint->handphone }} (Bimbit)</td>
                                            </tr>
                                            <tr style="border-top: 1px solid #e5e5e5;">
                                                <td style="padding-left: 20px"><i class="icon-envelope"></i></td>
                                                <td>{{ $complaint->email }}</td>
                                            </tr>
                                        </table>     
                                    </li>
                                </ul>

                                <div class="panel-body aside-border">
                                    <h5 style="color: #0088CC">Maklumat Pelaksana</h5>
                                </div>

                                <ul class="list-unstyled">
                                    <li>
                                        <input type="hidden" id="id" value="{{ $complaint->id }}">
                                        <table width="100%" class="table noborder">
                                            <tr>
                                                <td width="25%" style="padding-left: 20px"><strong>Kategori</strong></td>
                                                <td>
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
                                                </td>
                                            </tr>
                                            <tr style="border-top: 1px solid #e5e5e5;">
                                                <td style="padding-left: 20px"><strong>Sub Kategori</strong></td>
                                                <td>
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
                                                </td>
                                            </tr>
                                            <tr style="border-top: 1px solid #e5e5e5; display: none;" id="details">
                                                <td style="padding-left: 20px"><strong>Perincian</strong></td>
                                                <td>
                                                    @if($complaint->status_id == 4)

                                                        <p class="form-control-static" style="white-space: normal;">{{ (!empty($complaint->subcategory_id)) ? App\Helper\Utilities::getSubCategory($complaint->subcategory_id) : "-"  }}</p>
                                                        
                                                    @else
                                                    <div class="col-md-8">
                                                        <select multiple class="form-control details" name="detail">
                                                            <option value="">Sila Pilih</option>
                                                            @foreach($details as $row)
                                                            <option value="{{ $row->id }}" {{ $complaint->detail_id == $row->id ? 'selected' : '' }} >{{ $row->detail_desc }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr style="border-top: 1px solid #e5e5e5;">
                                                <td style="padding-left: 20px"><strong>Pelaksana</strong></td>
                                                <td>
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
                                                            <p class="form-control-static" style="white-space: normal;">
                                                            @if(isset($tasks->user_id))
                                                                @if($tasks->user_id != Auth::user()->id)
                                                                    {!! (!empty($tasks->user_id)) ? App\Helper\Utilities::getStaffName($tasks->user_id)."<br>" : ""  !!}
                                                                    <button class="btn btn-danger btn-xs" id="take">Ambil Tindakan</button>
                                                                @else
                                                                    {!! (!empty($tasks->user_id)) ? App\Helper\Utilities::getStaffName($tasks->user_id)."<br>" : ""  !!}    
                                                                @endif
                                                            @else    
                                                                <button class="btn btn-danger btn-xs" id="take">Ambil Tindakan</button>
                                                            @endif
                                                            </p>
                                                        @endif <!-- !end pentadbir -->

                                                    @endif <!-- !end status -->
                                                </td>
                                            </tr>
                                        </table>

                                    </li>
                                </ul>

                                
                            </div>

                        </div>
                        
                        <!-- button group -->
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6 text-left"><a href="{{ route('complaintlist') }}" class="btn btn-warning"><i class="ti ti-control-backward"></i> Kembali</a></div>
                                <div class="col-md-6 text-right">
                                    
                                    <div class="btn-group">
                                        <!-- <button class="btn btn-default btn-success" id="done" value="{{ $complaint->id }}" @if($complaint->status_id == 4) disabled="" @endif type="button" data-toggle="tooltip" data-placement="bottom" data-original-title="Aduan Selesai"><i class="icon-check"></i></button>
                                         <button class="btn btn-default btn-info" id="print" value="{{ $complaint->id }}" type="button" data-toggle="tooltip" data-placement="bottom" data-original-title="Cetak"><i class="icon-printer"></i></button> -->

                                        @if($tasks)

                                            @if($tasks->user_id)
                                                <a target="_blank" href="https://wa.me/6{{ App\Helper\Utilities::phonenumber($tasks->user_id) }}/?text=*Sistem Helpdesk* :%0A{{ $complaint->name }}%0A{{ $complaint->location }}%0A{{ $complaint->sector_desc }}%0A{{ $complaint->department_desc }}%0A%0A{{ $complaint->remarks }}" class="btn btn-success" value="{{ $complaint->id }}" type="button" data-toggle="tooltip" data-placement="top" data-original-title="Whatsapp"><i class="icon-bubble"></i></a>
                                            @else
                                                <a href="#" class="btn btn-success" disabled type="button" data-toggle="tooltip" data-placement="top" data-original-title="Whatsapp"><i class="icon-bubble"></i></a>
                                            @endif

                                        @else
                                            <a href="#" class="btn btn-success" disabled type="button" data-toggle="tooltip" data-placement="top" data-original-title="Whatsapp"><i class="icon-bubble"></i></a>
                                        @endif
                                        
                                        @if($tasks)

                                            @if($tasks->user_id)
                                                <a href="#" class="email btn btn-warning" data-id="{{ $complaint->id }}" type="button" data-toggle="tooltip" data-placement="top" data-original-title="Email"><i class="icon-envelope-open"></i></a>
                                            @else
                                                <a href="#" class="btn btn-warning" disabled type="button" data-toggle="tooltip" data-placement="top" data-original-title="Email"><i class="icon-envelope-open"></i></a>
                                            @endif

                                        @else
                                            <a href="#" class="btn btn-warning" disabled type="button" data-toggle="tooltip" data-placement="top" data-original-title="Email"><i class="icon-envelope-open"></i></a>
                                        @endif

                                        <button class="btn btn-danger" id="delete" value="{{ $complaint->id }}" type="button" data-toggle="tooltip" data-placement="top" data-original-title="Hapus Aduan"><i class=" icon-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="Box-innerContent">
                        <div class="Box niceScroll">

                            <div class="Box__body">
                                <div class="panel-body">
                                        @if($complaint->status_id == 4)
                                        <h5 style="color: #0088CC; margin-bottom: 15px;"><strong>Ulasan</strong></h5>
                                        @endif
                                    <ul class="task-sum-list">
                                        
                                        <!-- ulasan -->                
                                        @if($complaint->status_id != 4)
                                        <div class="activity-item" style="margin-bottom: 40px;">
                                            <form method="post" action="{{ route('taskremarks') }}" autocomplete="off" class="form-horizontal">
                                                @csrf
                                                <input type="hidden" name="complaint_id" value="{{ $complaint->id }}">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label class="col-lg-3 control-label">Status</label>
                                                            <div class="col-lg-9">
                                                                <select class="form-control status" name="status">
                                                                    <option value="">Status Pilih</option>
                                                                    @foreach($status as $row)
                                                                    <option value="{{ $row->id }}" @if(!empty($complaint->status_id) && $complaint->status_id == $row->id) selected @endif >{{ $row->status_desc }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-7" id="officers" style="display: none;">
                                                        <div class="form-group">
                                                            <label class="col-lg-3 control-label">Pegawai</label>
                                                            <div class="col-lg-9">
                                                                <select class="form-control officer" name="officer">
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
                                        <li class="ulas">
                                            <div class="row">
                                                <div class="col-lg-10"><b>{{ $data->name }}</b> <small>{{ Date('d/m/Y h:i A', strtotime($data->created_at)) }}</small></div>
                                                
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-10">
                                                    <p style="white-space: pre-wrap;">{{ $data->remarks }}</p>
                                                </div>
                                                @if(Auth::user()->id == $data->user_id && $complaint->status_id != 4)
                                                <div class="col-lg-2 pull-right">
                                                    <a href="#" style="text-decoration: none;" class="editRemark" data-id="{{ $data->id }}" title="Kemaskini"><i class="fa fa-pencil" style=" font-size: 16px;"></i></a>
                                                    <a href="#" style="text-decoration: none;" class="delRemark" data-id="{{ $data->id }}" title="Hapus"><i class="fa fa-trash" style=" font-size: 16px;"></i></a>
                                                </div>
                                                @endif
                                            </div>
                                            
                                        </li>
                                        @endforeach
                                        <!-- end looping komen pelaksana dan pentadbir -->

                                        <li class="ulas">
                                            <div class="tsk-title" style="white-space: pre-wrap;"><b>{{ $complaint->name }}</b> <small>{{ Date('d/m/Y h:i A', strtotime($complaint->date_open)) }}</small><br>{{ $complaint->remarks }}</div>
                                            <div style="margin-top: 5px">
                                            @if(!empty($complaint->attachment))<br><a class="attachment" href="{{ asset('uploads/'.$complaint->attachment) }}" target="_blank" style="text-decoration: none;" title="Muatturun"><i class="fa fa-download" style="color: #62549a; font-size: 18px"></i>&nbsp;&nbsp;&nbsp;<small style="color: #62549a">{{ $complaint->attachment }}</small></a>@endif
                                            </div>
                                        </li>

                                    </ul>
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