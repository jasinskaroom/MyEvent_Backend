<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>QRCode</title>

        @include('templates.pdf.styles.qrcode_style')
    </head>
    <body>
        @foreach($qrcodes as $qrcode)
            <div class="qrcode-holder">
                <div class="thumbnail">
                    <img src="{{ $qrcode->base64Image() }}" alt="">
                    <div class="caption">
                        <h3>Point: {{ $qrcode->point }}</h3>
                    </div>
                </div>
            </div>
            @if ($loop->iteration % 6 == 0)
                <div class="page-break"></div>
            @endif
        @endforeach
    </body>
</html>
