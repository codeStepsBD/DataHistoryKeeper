<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.11.4/themes/ui-lightness/jquery-ui.css">
    <title>Document</title>
    <style>
        body{
            background: linear-gradient(45deg, #4158d0, #c850c0);
            background-attachment: fixed;
        }
        .table{
            border-radius: 10px;
            background: #ffffff;
        }
        .table thead tr{
            background: #36304a;
        }
        .table thead tr th{
            font-family: OpenSans-Regular;
            padding: 11px 12px;
            font-size: 18px;
            text-transform: capitalize;
            color: #fff;
            border-style: none;
        }
        .table tr td {
            border-style: none;
            padding: 14px 13px;
        }
        .table tbody tr:hover{
            background: #f5f5f5;
            cursor: pointer;
        }
        .navbar a{
            font-size: 16px;
        }
        .navbar_area{
            margin-bottom: 14px;
        }
        .page-title{
            margin-bottom: 26px;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div id="history-body-area">
        <div class="container navbar_area">
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <a class="navbar-brand btn btn-dark" href="{{ route('history-keeper.index') }}">History Data</a>
                <a class="navbar-brand btn btn-dark" aria-current="page" href="{{ route('history-keeper.configuration.create') }}">Configuration</a>
            </nav>
        </div>
        <div class="container">
            @yield('content')
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
    @stack('custom-scripts')
</body>
</html>
