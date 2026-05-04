@extends('layout.userlayout')

@section('content')
<style>

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
          <div class="panel-heading">Soalan Lazim</div>
          <div class="panel-body table-responsive">
          	
          	<div class="panel-accordion panel-group">
							@php
							$bil = 1;
							@endphp
							@foreach($faqs as $data)
							<div class="panel panel-default">
								<div class="panel-heading" id="heading4">
									<h4 class="panel-title">
									<a role="button" data-toggle="collapse" href="#collapse{{ $data->id }}" aria-expanded="false" aria-controls="collapse1" class="collapsed">
									{{ $bil++.'. '.$data->question }}
									</a>
									</h4>
								</div>
								<div id="collapse{{ $data->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading4" aria-expanded="false" style="">
									<div class="panel-body">
									{{ $data->answer }}
									</div>
								</div>
							</div>
							@endforeach
						</div>

          </div>
        </section>
      </div>
    </div>
  </div>
</div>
@endsection