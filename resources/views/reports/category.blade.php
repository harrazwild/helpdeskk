
@extends('layout.mainlayout')

@section('content')
<style>
tbody.collapse.in 
{
  display: table-row-group;
}
</style>
<script type="text/javascript">
  $(document).ready(function() {
    // Then attach the picker to the element you want to trigger it
    var oDate = $('input[name="daterange"]').daterangepicker({
                  startDate: '01-01-2021', 
                  endDate: '31-12-2021',
                  locale: {
                            cancelLabel: 'Clear',
                            format: 'DD-MM-YYYY'
                          }
                });

    var date = $('#daterange').val();
    var cat = $('#category').val();

    loadReport(date, cat);

    oDate.on('change', function(){
      var d = $('#daterange').val();
      var c = $('#category').val();

      loadReport(d, c);
    });

    $('#category').on('change', function(){
      var d = $('#daterange').val();
      var c = $('#category').val();

      loadReport(d, c);
    });


  });

  function loadReport(d, c)
  {
    var url = '{{ route("getReport1") }}';

    $.ajax({
      type:"POST",
      url: url,
      data:{
              "_token": '{{ csrf_token() }}',
              "d": d,
              "c": c
            },
      success:function(res){        
        console.log(res);
      }
    });
  }
</script>
<div class="ui-content-body">
  <div class="ui-container">

    <div class="row">
      <div class="col-sm-12">
        <section class="panel panel-default">
          <div class="panel-heading">Laporan Mengikut Kategori Aduan</div>
          <div class="panel-body table-responsive">
              
            <form class="form-horizontal" method="get">
            @csrf

              <div class="form-group">
                  <label for="sector" class="col-sm-2 control-label">Tempoh</label>
                  <div class="col-lg-3">
                    <input class="form-control" type="text" name="daterange" id="daterange">
                  </div>
              </div>

              <div class="form-group">
                  <label for="sector" class="col-sm-2 control-label">Kategori</label>
                  <div class="col-lg-3">
                    <select class="form-control" id="category" name="category">
                      <option value="" >Sila Pilih</option>
                      @foreach($categories as $row)
                      <option value="{{ $row->id }}" >{{ $row->category_desc }}</option>
                      @endforeach
                    </select>
                  </div>
              </div>

              <div class="form-group row">
                  <label class="col-sm-2 col-form-label">&nbsp;</label>
                  <div class="col-sm-10 text-left">
                    <input type="submit" class="btn btn-success" style="min-width:100px" value="Hantar">
                    <a href="{{ route('category_report') }}" class="btn btn-warning">Semula</a>
                  </div>
              </div>
        
            </form>

            <table class="table table-border table-striped report_1">
              <thead>
                <tr class="bg-primary">
                  <th></th>
                  <th></th>
                  <th></th>
                  <th>Perkara</th>
                  <th width="10%" align="center" style="border-left: 1px solid">Jumlah</th>
                </tr>
              </thead>
              <tbody>
                @php
                $x = 1;
                @endphp
                @if(!$categories->isEmpty())
                @foreach($categories as $row) <!-- looping kategori -->
                <tr>
                  <td align="right" width="2%"><strong>{{ $x++ }}.</strong></td>
                  <td colspan="4"><strong>{{ $row->category_desc }}</strong></td>
                </tr>
                  
                
                <tr id="subcat" style="background-color: #d1d9d9">
                  <!-- subcat -->
                </tr>
<!-- details -->    
                
                <tr style="background-color: #d1d9d9">
                  <td>&nbsp;</td>
                  <td align="right" width="2%"></td>
                  <td colspan="2">Tiada Maklumat</td>
                  <td align="center" style="border-left: 1px solid"><strong></strong></td>
                </tr>

                @endforeach
                @else
                <tr>
                  <td colspan="5">Tiada Maklumat</td>
                </tr>
                @endif    
              </tbody>
            </table>
          </div>

        </section>
      </div>

    </div>

  </div>
</div>
@endsection
