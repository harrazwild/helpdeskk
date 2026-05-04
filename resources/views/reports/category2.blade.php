@extends('layout.mainlayout')

@section('content')
    <style>
        tbody.collapse.in {
            display: table-row-group;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function() {
            // Then attach the picker to the element you want to trigger it
            var oDate = $('input[name="daterange"]').daterangepicker({
                startDate: '{{ $sDate }}',
                endDate: '{{ $eDate }}',
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD-MM-YYYY'
                }
            });
        });
    </script>
    @php
        if (isset($cat)) {
            $cat = $cat;
        } else {
            $cat = 0;
        }
    @endphp
    <div class="ui-content-body">
        <div class="ui-container">

            <div class="row">
                <div class="col-sm-12">
                    <section class="panel panel-default">
                        <div class="panel-heading">Laporan Mengikut Kategori Aduan</div>
                        <div class="panel-body table-responsive">
                            <form class="form-horizontal" method="get">
                                @csrf

                                <div class="form-group">
                                    <label for="sector" class="col-sm-2 control-label">Tempoh</label>
                                    <div class="col-lg-3">
                                        <input class="form-control" type="text" name="daterange">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="sector" class="col-sm-2 control-label">Kategori</label>
                                    <div class="col-lg-3">
                                        <select class="form-control" id="category" name="category">
                                            <option value="">Sila Pilih</option>
                                            @foreach ($category as $row)
                                                <option value="{{ $row->id }}"
                                                    @if (!empty($cat) && $cat == $row->id) selected @endif>
                                                    {{ $row->category_desc }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">&nbsp;</label>
                                    <div class="col-sm-10 text-left">
                                        <input type="submit" class="btn btn-success" style="min-width:100px"
                                            value="Hantar">
                                        <a href="{{ route('category_report') }}" class="btn btn-warning">Semula</a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </section>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="text-right" style="margin-bottom: 5px; margin-right: 10px">
                        <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-info dropdown-toggle" type="button"
                                aria-expanded="false">Cetak <span class="caret"></span></button>
                            <ul role="menu" class="dropdown-menu">
                                <li><a href="{{ route('categoryPDF', ['sDate' => $sDate, 'eDate' => $eDate, 'cat' => $cat]) }}"
                                        target="_blank">PDF</a></li>
                                <li><a href="{{ route('categoryExcel', ['sDate' => $sDate, 'eDate' => $eDate, 'cat' => $cat]) }}"
                                        target="_blank">Excel</a></li>
                            </ul>
                        </div>
                    </div>
                    <section class="panel panel-default">
                        <div class="panel-body table-responsive">
                            <table class="table table-border table-striped report_1">
                                <thead>
                                    <tr class="bg-primary">
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th>Perkara</th>
                                        <th width="10%" align="center" style="border-left: 1px solid">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $x = 1;
                                    @endphp
                                    @if (!$categories->isEmpty())
                                        @foreach ($categories as $row)
                                            <!-- looping kategori -->
                                            <tr>
                                                <td align="right" width="2%"><strong>{{ $x++ }}.</strong></td>
                                                <td colspan="4"><strong>{{ $row->category_desc }}</strong></td>
                                            </tr>

                                            @php
                                                $subcats = App\Helper\Report::getSubCategory($row->id);
                                                $y = 1;
                                            @endphp
                                            @foreach ($subcats as $data)
                                                <!-- looping subkategori -->
                                                <tr style="background-color: #d1d9d9">
                                                    <td align="center" class="expand-button"></td>
                                                    <td align="right" width="2%">
                                                        {{ App\Helper\Report::toAlpha($y++) }}.</td>
                                                    <td colspan="2">{{ $data['subcategory_desc'] }}</td>
                                                    <td align="center" style="border-left: 1px solid;">
                                                        <strong>{{ App\Helper\Report::totalSubC($row->id, $data['id'], $s_date, $e_date) }}</strong>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr style="background-color: #d1d9d9">
                                                <td>&nbsp;</td>
                                                <td align="right" width="2%">{{ App\Helper\Report::toAlpha($y++) }}.
                                                </td>
                                                <td colspan="2">Lain-lain</td>
                                                <td align="center" style="border-left: 1px solid">
                                                    <strong>{{ App\Helper\Report::totalSubC($row->id, '', $s_date, $e_date) }}</strong>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5">Tiada Maklumat</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </div>

        </div>
    </div>
@endsection
