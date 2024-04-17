@extends('historyKeeper::layouts')
@section('content')
    <div>
        <form method="post" action="{{ route('table.store') }}">
            @foreach($tableList as $table)
                <ul>
                    <li><input type="checkbox" name="tables[]" value="{{$table}}"> {{$table}}</li>
                </ul>
            @endforeach
            <input type="submit">
        </form>
    </div>
@stop


