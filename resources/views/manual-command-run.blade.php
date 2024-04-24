@extends('historyKeeper::layouts')
@section('content')
    <style>
        h1{
            font-size: 31px;
            margin: 20px 0px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table td{
            padding: 8px 6px;
        }
        table, td, th {
            border: 1px solid;
        }
        input[type='submit']{
            padding: 10px 47px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 28px;
        }
    </style>
    <div>
        <h1>Select Command Run on web</h1>
        <ul>
            <li><a href="{{route('commandUrl',"runTest")}}" target="_blank">Run Test</a></li>
            <li><a href="{{route('commandUrl',"scanMismatch")}}" target="_blank">Scan Mismatch</a></li>
            <li><a href="{{route('commandUrl',"true")}}" target="_blank">Make New History Tables (Danger: this will delete your all old data)</a></li>
        </ul>

    </div>
@stop


