<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td align="center">Laporan Statistik KPI Pegawai Teknikal</td>
		</tr>
		<tr>
			<td align="center">Bahagian Teknologi Maklumat Dan Komunikasi</td>
		</tr>
		<tr>
			<td align="center">JABATAN AUDIT NEGARA</td>
		</tr>
	</table>
	<br><br>
	<table width="100%" cellpadding="5px" cellspacing="0" border="1">
	  <tr style="background-color: #62549a; color: #fff">
	  <th width="2%">Bil</th>
      <th>Nama</th>
      <th width="25%">Jawatan</th>
      <th width="10%">Lebih 3 Hari</th>
      <th width="10%">Lebih 5 Hari</th>
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
	<p style="font-size: 8pt; font-style: italic;">Tarikh Cetakan : {{ $today }}</p>
</body>
</html>