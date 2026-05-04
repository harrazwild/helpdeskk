<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td align="center">Laporan Perincian Pegawai Teknikal</td>
		</tr>
		<tr>
			<td align="center">Bahagian Teknologi Maklumat Dan Komunikasi</td>
		</tr>
		<tr>
			<td align="center">JABATAN AUDIT NEGARA</td>
		</tr>
	</table>
	<br><br>
	<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 20px">
		<tr>
			<td width="50%">Nama : {{ App\Helper\Utilities::getStaffName($s) }}</td>
			<td>Jawatan : {{ App\Helper\Utilities::getStaffPosition($s) }}</td>
		</tr>
	</table>
	<table width="100%" cellpadding="5px" cellspacing="0" border="1">
	  <tr style="background-color: #62549a; color: #fff">
	    <th rowspan="2" width="2%">Bil</th>
	    <th rowspan="2" width="8%">No Aduan</th>
	    <th rowspan="2">Nama Pengadu</th>
	    <th rowspan="2">Sektor/Bahagian</th>
	    <th rowspan="2">Status Aduan</th>
	    <th rowspan="2" width="12%">Tarikh Aduan</th>
	    <th colspan="2" width="12%">Aduan Selesai</th>
	    <th colspan="2" width="8%">Aduan Ditutup</th>
	  </tr>
		<tr style="background-color: #62549a; color: #fff">
			<td width="8%">Tarikh</td>
			<td width="8%">Tempoh</td>
			<td width="8%">Tarikh</td>
			<td width="8%">Tempoh</td>
		</tr>
	  @php  
	  $bil = 1;
	  @endphp
	  @if(!$complaints->isEmpty())
	  @foreach($complaints as $row)
	  <tr>
	    <td align="right">{{ $bil++ }}.&nbsp;</td>
	    <td align="center">#{{ $row->application_no }}</td>
	    <td>{{ $row->name }}</td>
	    <td>{!! App\Helper\Utilities::getSector($row->sector_code).'<br>'.App\Helper\Utilities::getDepartment($row->department_code) !!}</td>
	    <td>{{ $row->status_desc }}</td>
	    <td align="center">{{ date('d-m-Y', strtotime($row->date_open)) }}</td>
	    <td align="center">{{ (!empty($row->date_job_done)) ? date('d-m-Y', strtotime($row->date_job_done)) : "-"  }}</td>
		<td align="center">{{ $row->tempoh_selesai }} hari</td>
		<td align="center">{{ (!empty($row->date_close)) ? date('d-m-Y', strtotime($row->date_close)) : "-"  }}</td>
		<td align="center">{{ $row->tempoh_ditutup }} hari</td>
	  </tr>
	  @endforeach
	  @else
	  <tr>
	    <td colspan="10" align="center">Tiada Maklumat</td>
	  </tr>
	  @endif
	</table>
	<p style="font-size: 8pt; font-style: italic;">Tarikh Cetakan : {{ $today }}</p>
</body>
</html>