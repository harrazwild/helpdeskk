<table>
		<tr>
			<td>Nama : {{ App\Helper\Utilities::getStaffName($s) }}</td>
			<td>Jawatan : {{ App\Helper\Utilities::getStaffPosition($s) }}</td>
		</tr>
	</table>
<table>
  <tr>
    <th rowspan="2">Bil</th>
    <th rowspan="2">No Aduan</th>
    <th rowspan="2">Nama Pengadu</th>
    <th rowspan="2">Sektor/Bahagian</th>
    <th rowspan="2">Status Aduan</th>
    <th rowspan="2">Tarikh Aduan</th>
    <th colspan="2" align="center">Aduan Selesai</th>
    <th colspan="2" align="center">Aduan Ditutup</th>
  </tr>
  <tr>
    <td>Tarikh</td>
    <td>Tempoh</td>
    <td>Tarikh</td>
    <td>Tempoh</td>
  </tr>
  @php  
  $bil = 1;
  @endphp
  @if(!$complaints->isEmpty())
  @foreach($complaints as $row)
  <tr>
    <td>{{ $bil++ }}.&nbsp;</td>
    <td>#{{ $row->application_no }}</td>
    <td>{{ $row->name }}</td>
    <td>{!! App\Helper\Utilities::getSector($row->sector_code).'<br>'.App\Helper\Utilities::getDepartment($row->department_code) !!}</td>
    <td>{{ $row->status_desc }}</td>
    <td>{{ date('d-m-Y', strtotime($row->date_open)) }}</td>
    <td>{{ (!empty($row->date_job_done)) ? date('d-m-Y', strtotime($row->date_job_done)) : "-"  }}</td>
    <td>{{ $row->tempoh_selesai }} hari</td>
    <td>{{ (!empty($row->date_close)) ? date('d-m-Y', strtotime($row->date_close)) : "-"  }}</td>
    <td>{{ $row->tempoh_ditutup }} hari</td>
  </tr>
  @endforeach
  @else
  <tr>
    <td colspan="10" align="center">Tiada Maklumat</td>
  </tr>
  @endif
</table>