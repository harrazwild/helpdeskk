@extends('layout.mainlayout')

@section('content')
<style>
hr
{
	margin-top: 6px;
	margin-bottom: 6px;
}
</style>
<script type="text/javascript">
$(document).ready(function(){

});
</script>
<div class="ui-content-body">  
  <div class="ui-container">

    <div class="row">
      <div class="col-sm-12">
        <section class="panel panel-default">
        	<div class="panel-heading">Maklumat Pengguna</div>
          <div class="panel-body table-responsive">
            <form class="form-horizontal">

              <div class="row">
                  <div class="col-sm-6">
                      <div class="form-group">
                          <label class=" col-sm-4 control-label">Peranan</label>
                          <div class="col-lg-6">
                              <p class="form-control-static">{{ (!empty($user->role_desc)) ? $user->role_desc : "-"  }}</p>
                          </div>
                      </div>
                  </div>
                  <div class="col-sm-6">
                      <div class="form-group">
                          <label class=" col-sm-3 control-label">No Kad Pengenalan</label>
                          <div class="col-lg-6">
                              <p class="form-control-static">{{ (!empty($user->ic_number)) ? $user->ic_number : "-"  }}</p>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="form-group">
                  <label class=" col-sm-2 control-label">Nama</label>
                  <div class="col-lg-6">
                      <p class="form-control-static">{{ (!empty($user->name)) ? $user->name : "-"  }}</p>
                  </div>
              </div>
              <div class="row">
                  <div class="col-sm-6">
                      <div class="form-group">
                          <label class=" col-sm-4 control-label">Gred</label>
                          <div class="col-lg-6">
                              <p class="form-control-static">{!! $user->grade_desc !!}</p>
                          </div>
                      </div>
                  </div>
                  <div class="col-sm-6">
                      <div class="form-group">
                          <label class=" col-sm-3 control-label">Jawatan</label>
                          <div class="col-lg-6">
                              <p class="form-control-static">{!! $user->position_desc !!}</p>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-sm-6">
                      <div class="form-group">
                          <label class=" col-sm-4 control-label">Sektor</label>
                          <div class="col-lg-6">
                              <p class="form-control-static">{!! $user->sector_desc !!}</p>
                          </div>
                      </div>
                  </div>
                  <div class="col-sm-6">
                      <div class="form-group">
                          <label class=" col-sm-3 control-label">Bahagian</label>
                          <div class="col-lg-8">
                              <p class="form-control-static">{!! $user->department_desc !!}</p>
                          </div>
                      </div>
                  </div>
              </div>
              @if($user->department_code == '2.1.3')
              <div class="form-group" id="sectionDIV">
                  <label class=" col-sm-2 control-label">Seksyen</label>
                  <div class="col-lg-4">
                      <p class="form-control-static">{{ (!empty($user->section_desc)) ? $user->section_desc : "-"  }}</p>
                  </div>
              </div>
              @endif
              <div class="row">
                  <div class="col-sm-4">
                      <div class="form-group">
                          <label class=" col-sm-6 control-label">Blok</label>
                          <div class="col-lg-6">
                              <p class="form-control-static">{{ (!empty($user->block)) ? $user->block : "-"  }}</p>
                          </div>
                      </div>
                  </div>
                  <div class="col-sm-4">
                      <div class="form-group">
                          <label class=" col-sm-4 control-label">Aras</label>
                          <div class="col-lg-6">
                              <p class="form-control-static">{{ (!empty($user->level)) ? $user->level : "-"  }}</p>
                          </div>
                      </div>
                  </div>
                  <div class="col-sm-3">
                      <div class="form-group">
                          <label class=" col-sm-4 control-label">Zon</label>
                          <div class="col-lg-8">
                              <p class="form-control-static">{{ (!empty($user->zone)) ? $user->zone : "-"  }}</p>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-sm-4">
                      <div class="form-group">
                          <label class=" col-sm-6 control-label">No Telefon (Pejabat)</label>
                          <div class="col-lg-6">
                              <p class="form-control-static">{{ (!empty($user->telephone)) ? $user->telephone : "-"  }}</p>
                          </div>
                      </div>
                  </div>
                  <div class="col-sm-4">
                      <div class="form-group">
                          <label class=" col-sm-6 control-label">No Telefon (Peribadi)</label>
                          <div class="col-lg-6">
                              <p class="form-control-static">{{ (!empty($user->handphone)) ? $user->handphone : "-"  }}</p>
                          </div>
                      </div>
                  </div>
                  <div class="col-sm-3">
                      <div class="form-group">
                          <label class=" col-sm-2 control-label">Emel</label>
                          <div class="col-lg-10">
                              <p class="form-control-static">{{ (!empty($user->email)) ? $user->email : "-"  }}</p>
                          </div>
                      </div>
                  </div>
              </div>
                        
            </form>
          </div>
          <div class="panel-footer">
          	<a href="{{ route('user') }}" class="btn btn-info">Kembali</a>
          </div>
        </section>
      </div>
    </div>

  </div>
</div>
@endsection