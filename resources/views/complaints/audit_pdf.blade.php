<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td rowspan="3" width="15%"><img height="72px" src="{{ asset('imgs/Logo_M.png') }}"></td>
		</tr>
		<tr>
			<td>Bahagian Teknologi Maklumat Dan Komunikasi</td>
		</tr>
		<tr>
			<td>JABATAN AUDIT NEGARA</td>
		</tr>
	</table>
	<br><br>
	<table width="100%" cellpadding="5" cellspacing="0" border="0">
		<tr style="">
			<td align="center" colspan="3" style="background-color: #4aa9e9; border-top: 1px solid #626262; border-bottom: 1px solid #626262; border-left: 1px solid #626262; border-right: 1px solid #626262">Jejak Audit #{{ $app_no }}</td>
		</tr>
    @foreach($logs as $row)
    @php

      if($row->status == 1 && $row->description == 'Log Aduan Baru (BTM)'){
        $remark = "Pegawai BTM log aduan ke sistem";
      }elseif($row->status == 1 && $row->description == 'Pengadu Log Aduan Baru'){
        $remark = "Pengadu log aduan ke sistem";  
      }elseif($row->status == 2 && $row->description == 'Kemaskini Aduan'){
        $remark = "Penyelaras Sistem menyerahkan tugas kepada :<br><i>".$row->pelaksana."</i>";
      }elseif($row->status == 2 && $row->description == 'Ambil Tindakan'){
        $remark = "Pegawai Teknikal mengambil alih tugas";
      }elseif($row->status == 2 && $row->description == 'Pegawai Kemaskini Aduan'){
        $remark = "Aduan diserah kepada Pegawai Teknikal :<br><i>".$row->pelaksana."</i>";
      }elseif($row->status == 3){
        $remark = "Pegawai Teknikal menghantar aduan ke peringkat pegawai :<br><i>".$row->pegawai."</i>";
      }elseif($row->status == 4){
        $remark = "Aduan selesai di peringkat pegawai";
      }elseif($row->status == 5){
        $remark = "Aduan selesai di peringkat pegawai teknikal";  
      }elseif($row->status == 6){
        $remark = "Pengadu mengesahkan aduan selesai";
      }elseif($row->status == 7){
        $remark = "Pengadu tidak mengesahkan aduan selesai";
      }elseif($row->status == 8){
        $remark = "Penyelaras Sistem menutup aduan";
      }elseif($row->status == 9){
        $remark = "Aduan diserahkan kepada Pembekal :<br><i>".$row->vendor_name."</i>";
      }elseif($row->description == 'Pegawai Tukar Pegawai Teknikal'){
        $remark = "Aduan diserahkan kepada Pegawai Teknikal :<br><i>".$row->pelaksana."</i>";       
      }

		@endphp
    <tr>
			<td valign="top" width="20%" style="border-top: 1px solid #626262; border-bottom: 1px solid #626262; border-left: 1px solid #626262">{!! date("d-m-Y", strtotime($row->created_at)).'<br>'.date("h:i a", strtotime($row->created_at)) !!}</td>
			<td valign="top" width="1%" style="border-top: 1px solid #626262; border-bottom: 1px solid #626262">:</td>
			<td style="border-top: 1px solid #626262; border-bottom: 1px solid #626262; border-right: 1px solid #626262">
      <strong>{{ $row->tindakan }}</strong><br>
      {!! $remark !!}
    </td>
		</tr>
		@endforeach
	</table>
  <br><br>
	<p style="font-size: 8pt; font-style: italic;">Tarikh Cetakan : {{ $today }}</p>

</body>
</html>