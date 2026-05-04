<!DOCTYPE html>
<html>

<head>
    <title></title>
</head>

<body>
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td align="center">Laporan Mengikut Kategori Aduan</td>
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
            <th>Bil</th>
            <th colspan="3">Perkara</th>
            <th width="10%" align="center">Jumlah</th>
        </tr>
        @php
            $x = 1;
        @endphp
        @if (!$categories->isEmpty())
            @foreach ($categories as $row)
                <!-- looping kategori -->
                <tr>
                    <td style="border-right: 0px solid #fff;" align="right" width="2%">
                        <strong>{{ $x++ }}.&nbsp;</strong>
                    </td>
                    <td style="border-left: 0px solid #fff;" colspan="4"><strong>{{ $row->category_desc }}</strong>
                    </td>
                </tr>
                @php
                    $subcats = App\Helper\Report::getSubCategory($row->id);
                    $y = 1;
                @endphp
                @foreach ($subcats as $data)
                    <!-- looping subkategori -->
                    <tr style="background-color: #d1d9d9" class="clickable" data-toggle="collapse"
                        data-target="#group-of-rows-{{ $data->id }}" aria-expanded="false"
                        aria-controls="group-of-rows-{{ $data->id }}">
                        <td style="border-right: 0px solid #fff;" align="center"></td>
                        <td style="border-left: 0px solid #fff; border-right: 0px solid #fff;" align="right"
                            width="2%">{{ App\Helper\Report::toAlpha($y++) }}.&nbsp;</td>
                        <td style="border-left: 0px solid #fff;" colspan="2">{{ $data['subcategory_desc'] }}</td>
                        <td align="center" style="border-left: 1px solid;">
                            <strong>{{ App\Helper\Report::totalSubC($row->id, $data['id'], $sDate, $eDate) }}</strong>
                        </td>
                    </tr>
                @endforeach
                <tr style="background-color: #d1d9d9">
                    <td style="border-right: 0px solid #fff;">&nbsp;</td>
                    <td style="border-left: 0px solid #fff; border-right: 0px solid #fff;" align="right"
                        width="2%">{{ App\Helper\Report::toAlpha($y++) }}.&nbsp;</td>
                    <td style="border-left: 0px solid #fff;" colspan="2">Lain-lain</td>
                    <td align="center" style="border-left: 1px solid">
                        <strong>{{ App\Helper\Report::totalSubC($row->id, '', $sDate, $eDate) }}</strong>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="5">Tiada Maklumat</td>
            </tr>
        @endif
    </table>
    <p style="font-size: 8pt; font-style: italic;">Tarikh Cetakan : {{ $today }}</p>
</body>

</html>
