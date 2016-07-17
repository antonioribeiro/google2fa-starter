<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato', sans-serif;
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 96px;
            }

            .key {
                font-size: 50px;
                font-weight: 800;
                color: blue;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">Google 2FA</div>

                <div class="key">{{ $key }}</div>

                <div class="title"><img src="{{ $url }}" alt=""></div>

                <form action="/check2fa" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="text" name="code">
                    <input type="submit" value="check">
                </form>

                @if ($valid)
                    <div style="color: green; font-weight: 800;">VALID</div>
                @else
                    <div style="color: red; font-weight: 800;">INVALID</div>
                @endif
            </div>
        </div>
    </body>
</html>
