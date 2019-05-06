<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">

    <title>{%lang Страница не найдена%}</title>

    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0">
    <meta name="format-detection" content="telephone=no">

    <link rel="icon" type="image/png" sizes="32x32" href="/img/icons/favicon.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/icons/favicon-16x16.png">
    <link rel="shortcut icon" href="/img/icons/favicon.png">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:200&amp;subset=cyrillic" rel="stylesheet">

</head>
<body>
<div id="app" :class="{ 'show-sidebar--menu': showMenu }">
    <style>
        html, body, #app {
            height: 100%;
            margin: 0;
        }

        #app {
            display: flex;
            align-items: center;
            height: 100%;
        }

        h1 {
            font-size: 60px;
            text-align: center;
            font-weight: 200;
            font-family: "Montserrat", sans-serif;
            color: #555;
            width: 100%;
            margin: 0 0 30px 0;
        }

        @media only screen and (max-width: 424px) {
            h1 {
                font-size: 40px;
            }
        }
    </style>

    <h1>{%lang Страница не найдена%}</h1>
</div>
</body>
</html>
