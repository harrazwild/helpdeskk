<table>
    <tr>
        <th>Bil</th>
        <th colspan="3">Perkara</th>
        <th align="center">Jumlah</th>
    </tr>
    @php
        $x = 1;
    @endphp
    @if (!$categories->isEmpty())
        @foreach ($categories as $row)
            <!-- looping kategori -->
            <tr>
                <td align="right"><strong>{{ $x++ }}.</strong></td>
                <td colspan="4"><strong>{{ $row->category_desc }}</strong></td>
            </tr>
            @php
                $subcats = App\Helper\Report::getSubCategory($row->id);
                $y = 1;
            @endphp
            @foreach ($subcats as $data)
                <!-- looping subkategori -->
                <tr>
                    <td align="center"></td>
                    <td align="right">{{ App\Helper\Report::toAlpha($y++) }}.</td>
                    <td colspan="2">{{ $data['subcategory_desc'] }}</td>
                    <td align="center">
                        <strong>{{ App\Helper\Report::totalSubC($row->id, $data['id'], $sDate, $eDate) }}</strong>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td align="right">{{ App\Helper\Report::toAlpha($y++) }}.</td>
                <td colspan="2">Lain-lain</td>
                <td align="center"><strong>{{ App\Helper\Report::totalSubC($row->id, '', $sDate, $eDate) }}</strong>
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="5">Tiada Maklumat</td>
        </tr>
    @endif
</table>
