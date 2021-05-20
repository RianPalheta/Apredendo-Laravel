<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $page->title }}</title>
</head>
<body>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }
        body {
            background-color: #f2f2f2;
        }
        main {
            padding: 2rem 4rem;
            max-width: 1000px;
            margin: 10px auto;
            background-color: #fff;
            border: 1px solid #eee;
            box-shadow: 0 3px 5px rgba(0, 0, 0, .1);
        }
    </style>
    <main>
        {!! $page->body !!}
    </main>
</body>
</html>
