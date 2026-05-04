<table>
  <tr>
    <th>Bil</th>
    <th>Nama</th>
    <th>Jawatan</th>
    <th>Lebih 3 Hari</th>
    <th>Lebih 5 Hari</th>
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
	  <td align="center">{{ App\Helper\Report::threedays($row->id, $sDate, $eDate) }}</td>
    <td align="center">{{ App\Helper\Report::fivedays($row->id, $sDate, $eDate) }}</td>
	</tr>
	@endforeach
  @else
  <tr>
    <td colspan="5" align="center">Tiada Maklumat</td>
  </tr>
  @endif
</table>