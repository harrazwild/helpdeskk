<!doctype html>
<html lang="en">
  <head>
    <title></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <style type="text/css">
      td {
        vertical-align: top;
      }
      .wrap {
        white-space: pre-wrap;
      }
    </style>

  </head>
  <body>

    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-sm-12 m-auto">
                <p> Tuan/Puan Yang Dihormati, </p>
                <br>
                <p> Berikut terdapat satu (1) aduan untuk perhatian pihak tuan/puan : </p>
                <table cellpadding="4" cellspacing="0" width="80%">
                  <tr>
                    <td width="20%">No. Permohonan</td>
                    <td width="1%">:</td>
                    <td><strong>{{ '#'.$data['app_no'] }}</strong></td>
                  </tr>
                  <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{ $data['name'] }}</td>
                  </tr>
                  <tr>
                    <td>Lokasi</td>
                    <td>:</td>
                    <td>
                      <strong>{{ $data['location'] }}</strong><br>
                      {{ $data['sector'] }}<br>
                      {{ $data['department'] }}<br>
                      {{ $data['lokasi'] }}
                    </td>
                  </tr>
                  @php
                    $d1 = strtotime($data['tkh_mula']);
                    $d2 = strtotime($data['tkh_tamat']);

                    if($d2 > $d1){
                      $date = date('d-m-Y', $d1).' - '.date('d-m-Y', $d2);
                    }else{
                      $date = date('d-m-Y', $d1);
                    }
                  @endphp
                  <tr>
                    <td>Tarikh</td>
                    <td>:</td>
                    <td>{{ $date }}</td>
                  </tr>
                  <tr>
                    <td>Masa</td>
                    <td>:</td>
                    <td>{{ date('h:i a', strtotime($data['masa_mula'])).' - '.date('h:i a', strtotime($data['masa_tamat'])) }}</td>
                  </tr>
                  @php
                    $it = explode('|', $data['remarks']);
                    $n = count($it);
                  @endphp
                  <tr>
                    <td>Maklumat Permohonan</td>
                    <td>:</td>
                    <td>
                      @if($n > 1)
                        @for($i = 0; $i < $n; $i++)
                        <li>{{ $it[$i] }}</li>
                        @endfor
                      @else
                      <li>{{ $it[0] }}</li>
                      @endif
                    </td>
                  </tr>
                </table>
                <br/>
                <br/>
                <p> Sekian, terima kasih</p>
                <p><em><small class="text-muted">Nota : Emel ini dijana oleh sistem dan tidak perlu di balas</small><em></p>
            </div>
        </div>
    </div>
  </body>
</html>