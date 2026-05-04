<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td align="center">Laporan Statistik Tindakan Pegawai Teknikal</td>
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
      <th width="18%">Jawatan</th>
      <th width="10%">Dalam Tindakan</th>
      <th width="10%">Tindakan Selesai</th>
      <th width="10%">Pengesahan Pengadu</th>
      <th width="10%">Aduan Ditutup</th>
      <th width="6%">Jumlah</th>
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
      <td align="center"><strong>{{ App\Helper\Report::total_task($row->id, $sDate, $eDate,) }}</strong></td>
		</tr>
		@endforeach
	  @else
	  <tr>
	    <td colspan="8" align="center">Tiada Maklumat</td>
	  </tr>
	  @endif
	</table>
	<p style="font-size: 8pt; font-style: italic;">Tarikh Cetakan : {{ $today }}</p>
</body>
</html>