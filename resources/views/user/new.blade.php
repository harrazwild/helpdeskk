<table class="table" id="complaint">
  <thead style="background-color: #62549a; color: #ffffff">
  <tr>
    <th width="8%">No Aduan</th>
    <th>Keterangan Aduan</th>
    <th width="10%">Tarikh Aduan</th>
    <th width="10%">Status</th>
    <th width="2%">Aktiviti</th>
  </tr>
  </thead>
  <tbody>
  @foreach($complaints as $data)
  <tr>
    <td><strong>#{{ $data->application_no }}</strong></td>
    <td>{{ $data->remarks }}</td>
    <td>{{ date('d-m-Y', strtotime($data->date_open)) }}</td>
    <td>
    @if($data->status_id == 1)
        <label class="label label-default">{{ $data->status_desc }}</label>
    @elseif($data->status_id == 2)
        <label class="label label-danger">{{ $data->status_desc }}</label>
    @elseif($data->status_id == 3)
        <label class="label label-warning">{{ $data->status_desc }}</label>
    @elseif($data->status_id == 4)
        <label class="label label-info">{{ $data->status_desc }}</label>
    @elseif($data->status_id == 5)
        <label class="label label-primary">{{ $data->status_desc }}</label>        
    @elseif($data->status_id == 6)
        <label class="label label-success">{{ $data->status_desc }}</label>
    @endif    
    </td>
    <td align="center">
        
    </td>
  </tr>
  @endforeach
  </tbody>
</table>