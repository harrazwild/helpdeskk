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
                <p> Berikut adalah maklumat akaun tuan/puan : </p>
                <table cellpadding="4" cellspacing="0" width="80%" style="font-size: 14pt">
                  <tr>
                    <td width="20%">ID Pengguna</td>
                    <td width="1%">:</td>
                    <td><strong>{{ $data['ic_number'] }}</strong></td>
                  </tr>
                  <tr>
                    <td>Katalaluan</td>
                    <td>:</td>
                    <td><strong>{{ $data['password'] }}</strong></td>
                  </tr>
                </table>
                <p>Tuan/Puan digalakkan untuk menukar katalaluan baru setelah log masuk ke sistem di pautan Profil Pengguna</p>
                <br/>
                <br/>
                <p> Sekian, terima kasih</p>
                <p><em><small class="text-muted">Nota : Emel ini dijana oleh sistem dan tidak perlu di balas</small><em></p>
            </div>
        </div>
    </div>
  </body>
</html>