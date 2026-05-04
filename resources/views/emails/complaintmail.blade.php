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
      .btn
      {
        display: inline-block;
        font-weight: 400;
        line-height: 1.5;
        color: #212529;
        text-align: center;
        text-decoration: none;
        vertical-align: middle;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
        background-color: transparent;
        border: 1px solid transparent;
        padding: .375rem .75rem;
        font-size: 1rem;
        border-radius: .25rem;
        transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        margin: .25rem .125rem;
      }
      .btn-info
      {
        color: #fff;
        background-color: #0d6efd;
        border-color: #0d6efd;
        cursor: pointer;
      }
    </style>

  </head>
  <body>

    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-sm-12 m-auto">
                <p> Tuan/Puan Yang Dihormati, </p>
                <br>
                <p> Berikut adalah maklumat aduan dari pihak tuan/puan : </p>
                <table cellpadding="4" cellspacing="0" width="80%" style="font-size: 14pt">
                  <tr>
                    <td width="20%">No aduan</td>
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
                      {{ $data['sector'] }}<br>
                      {{ $data['department'] }}<br>
                      {{ $data['lokasi'] }}<br>
                      {{ $data['location'] }}
                    </td>
                  </tr>
                  <tr>
                    <td>Maklumat Aduan</td>
                    <td>:</td>
                    <td class="wrap">{{ $data['remarks'] }}</td>
                  </tr>
                  <tr>
                    <td colspan="3">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="3" align="center"><a href="{{ route('check_log', Crypt::encrypt($data['id'])) }}" class="btn btn-info" style="text-decoration: none">Semak Aduan</a></td>
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