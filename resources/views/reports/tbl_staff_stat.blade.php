<table>
  <tr>
    <th>Bil</th>
    <th>Nama</th>
    <th>Jawatan</th>
    <th>Dalam Tindakan</th>
    <th>Tindakan Selesai</th>
    <th>Pengesahan Pengadu</th>
    <th>Aduan Ditutup</th>
    <th>Jumlah</th>
  </tr>
	@php  
	$bil = 1;
	@endphp
  @if(!$p->isEmpty())
	@foreach($p as $row)
	<tr>
	  <td align="right">{{ $bil++ }}.&nbsp;</td>
	  <td>{{ $row->name }}</td>
	  <td>{{ $row->position_desc }}</td>
	  <td align="center">{{ App\Helper\Report::task($row->id, $sDate, $eDate, 1) }}</td>
    <td align="center">{{ App\Helper\Report::task($row->id, $sDate, $eDate, 2) }}</td>
    <td align="center">{{ App\Helper\Report::task($row->id, $sDate, $eDate, 3) }}</td>
    <td align="center">{{ App\Helper\Report::task($row->id, $sDate, $eDate, 4) }}</td>
	  <td align="center"><strong>{{ App\Helper\Report::total_task($row->id, $sDate, $eDate) }}</strong></td>
	</tr>
	@endforeach
  @else
  <tr>
    <td colspan="8" align="center">Tiada Maklumat</td>
  </tr>
  @endif
</table>