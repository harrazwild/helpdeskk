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
  
});
</script>
<div class="ui-content-body">
    <div class="ui-container">
        <div class="row">
            <div class="col-md-12">

                <h3>#{{ $archive->norujukan }}</h3>

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
                                                <td>{{ $archive->fullname }}<br>{{ $archive->jawatan }}</td>
                                            </tr>
                                            <tr style="border-top: 1px solid #e5e5e5;">
                                                <td style="padding-left: 20px"><i class="icon-location-pin"></i></td>
                                                <td>{{ $archive->sektor }}<br>{{ $archive->bahagian }}</td>
                                            </tr>
                                            <tr style="border-top: 1px solid #e5e5e5;">
                                                <td style="padding-left: 20px"><i class="icon-phone"></i></td>
                                                <td>{!! (!empty($archive->notelephone)) ? $archive->notelephone : "-"  !!}</td>
                                            </tr>
                                            <tr style="border-top: 1px solid #e5e5e5;">
                                                <td style="padding-left: 20px"><i class="icon-envelope"></i></td>
                                                <td>{!! (!empty($archive->email)) ? $archive->email : "-"  !!}</td>
                                            </tr>
                                        </table>     
                                    </li>
                                </ul>

                                <div class="panel-body aside-border">
                                    <h5 style="color: #0088CC">Maklumat Aduan</h5>
                                </div>
                                <ul class="list-unstyled">
                                    <li>
                                        <table width="100%" class="table noborder">
                                            <tr>
                                                <td width="25%" style="padding-left: 20px"><strong>Kategori</strong></td>
                                                <td>
                                                    <p class="form-control-static" style="white-space: normal;">{!! $archive->JenisKerosakan !!}</p>
                                                </td>
                                            </tr>
                                            <tr style="border-top: 1px solid #e5e5e5;">
                                                <td style="padding-left: 20px"><strong>Perincian</strong></td>
                                                <td>
                                                    <p class="form-control-static" style="white-space: normal;">{{ $archive->details }}</p>
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
                                <div class="col-md-6 text-left"><a href="{{ route('archive') }}" class="btn btn-warning"><i class="ti ti-control-backward"></i> Kembali</a></div>
                            </div>
                        </div>

                    </div>
                    <div class="Box-innerContent">
                        <div class="Box niceScroll">

                            <div class="Box__body">
                                <div class="panel-body">
                                    <ul class="task-sum-list">
                                        
                                        <li class="">
                                            <div class="row">
                                                <div class="col-lg-10"><b>{{ $archive->fullname }}</b> <small>{{ Date('d/m/Y', strtotime($archive->Tarikh)) }}</small></div>
                                            </div>
                                            <div class="row" style="margin-top: 10px">
                                                <div class="col-lg-10">
                                                    <p style="white-space: pre-wrap;">{{ $archive->KeteranganMasalah }}</p>
                                                </div>
                                            </div>
                                        </li>

                                        <li class="ulas">
                                            <div class="row">
                                                <div class="col-lg-10"><b>{{ $archive->helpdesk2 }}</b> <small>{{ Date('d/m/Y', strtotime($archive->TarikhClosed)) }}</small></div>
                                            </div>
                                            <div class="row" style="margin-top: 10px">
                                                <div class="col-lg-10">
                                                    <p style="white-space: pre-wrap;">{{ $archive->Catatan }}</p>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-top: 10px">
                                                <div class="col-lg-10">
                                                    <p style="white-space: pre-wrap;">Status : {{ $archive->Status }}</p>
                                                </div>
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

    </div>
</div>
@endsection