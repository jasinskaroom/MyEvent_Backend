<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Gifts</title>

        @include('templates.pdf.styles.gifts_style')
    </head>
    <body>
        <h3>Gifts</h3>

        @foreach($giftPages as $gifts)
            <table>
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Point</th>
                </tr>
              </thead>
              <tbody>
                @foreach($gifts as $gift)
                    <tr>
                        <td>{{ $gift->name }}</td>
                        <td>{{ $gift->point }}</td>
                    </tr>
                @endforeach
              </tbody>
            </table>

            <div class="page-break"></div>
        @endforeach
    </body>
</html>
