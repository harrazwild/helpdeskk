<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td rowspan="3" width="15%"><img height="72px" src="{{ asset('imgs/Logo_M.png') }}"></td>
			<td>Maklumat Aduan</td>
		</tr>
		<tr>
			<td>Bahagian Teknologi Maklumat Dan Komunikasi</td>
		</tr>
		<tr>
			<td>JABATAN AUDIT NEGARA</td>
		</tr>
	</table>
	<br><br>
	@php
    $lokasi = '';
    if($complaint->block != '')
      $lokasi = "Blok ".$complaint->block;
    if($complaint->level != '')
      $lokasi .= ", ".$complaint->level;
    if($complaint->zone != '')
      $lokasi .= ", Zon ".$complaint->zone;
  @endphp
	<table width="100%" cellpadding="5" cellspacing="0" border="0">
		<tr style="">
			<td align="center" colspan="3" style="background-color: #4aa9e9; border-top: 1px solid #626262; border-bottom: 1px solid #626262; border-left: 1px solid #626262; border-right: 1px solid #626262">Maklumat Pengadu</td>
		</tr>
		<tr>
			<td valign="top" width="20%" style="border-left: 1px solid #626262">Nama / Jawatan</td>
			<td valign="top" width="1%">:</td>
			<td style="border-right: 1px solid #626262">{{ $complaint->name }}<br>{{ App\Helper\Utilities::getPosition($complaint->position_code) }} ({{ App\Helper\Utilities::getGrade($complaint->grade_code) }})</td>
		</tr>
		<tr>
			<td valign="top" style="border-left: 1px solid #626262">Lokasi</td>
			<td valign="top" width="1%">:</td>
			<td style="border-right: 1px solid #626262">{{ App\Helper\Utilities::getSector($complaint->sector_code) }}<br>{{ App\Helper\Utilities::getDepartment($complaint->department_code) }}<br>{{ $lokasi }}<br>{{ $complaint->location }}</td>
		</tr>
		<tr>
			<td valign="top" style="border-left: 1px solid #626262">Telefon</td>
			<td valign="top" width="1%">:</td>
			<td style="border-right: 1px solid #626262">{{ $complaint->telephone }} (Pejabat)<br>{{ $complaint->handphone }} (Bimbit)</td>
		</tr>
		<tr>
			<td valign="top" style="border-bottom: 1px solid #626262; border-left: 1px solid #626262">E-mel</td>
			<td valign="top" width="1%" style="border-bottom: 1px solid #626262">:</td>
			<td style="border-bottom: 1px solid #626262; border-right: 1px solid #626262">{{ $complaint->email }}</td>
		</tr>
	</table>
	<br>
	<table width="100%" cellpadding="5" cellspacing="0" border="0">
		<tr style="">
			<td align="center" colspan="3" style="background-color: #4aa9e9; border-top: 1px solid #626262; border-bottom: 1px solid #626262; border-left: 1px solid #626262; border-right: 1px solid #626262">Keterangan Aduan</td>
		</tr>
		<tr>
			<td valign="top" width="20%" style="border-left: 1px solid #626262">No Aduan</td>
			<td valign="top" width="1%">:</td>
			<td style="border-right: 1px solid #626262"><b>#{{ $complaint->application_no }}<b></td>
		</tr>
		<tr>
			<td valign="top" width="20%" style="border-left: 1px solid #626262">Tarikh / Masa</td>
			<td valign="top" width="1%">:</td>
			<td style="border-right: 1px solid #626262">{{ Date('d/m/Y h:i A', strtotime($complaint->date_open)) }}</td>
		</tr>
		<tr>
			<td valign="top" style="border-bottom: 1px solid #626262; border-left: 1px solid #626262">Keterangan</td>
			<td valign="top" width="1%" style="border-bottom: 1px solid #626262">:</td>
			<td style="white-space: pre-wrap; border-bottom: 1px solid #626262; border-right: 1px solid #626262">{{ $complaint->remarks }}</td>
		</tr>
	</table>
	<br>
	<table width="100%" cellpadding="5" cellspacing="0" border="0">
		<tr style="">
			<td align="center" style="background-color: #4aa9e9; border-top: 1px solid #626262; border-bottom: 1px solid #626262; border-left: 1px solid #626262; border-right: 1px solid #626262">Ulasan Pegawai Teknikal</td>
		</tr>
		@foreach($remarks as $data)
		<tr>
			<td style="border-bottom: 1px solid #626262; border-right: 1px solid #626262; border-left: 1px solid #626262">
				<b>{{ $data->name }}</b> <small>{{ Date('d/m/Y h:i A', strtotime($data->created_at)) }}</small>
				<br><span style="white-space: pre-wrap; margin-bottom: 5px">{{ $data->remarks }}</span>
			</td>
		</tr>
		@endforeach
	</table>
	<p style="font-size: 8pt; font-style: italic;">Tarikh Cetakan : {{ $today }}</p>

</body>
</html>