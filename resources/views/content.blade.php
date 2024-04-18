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
        <h1>Create History Table List</h1>
        <form method="post" action="{{ route('table.store') }}">
            <table>
                @foreach($tableList as $key=>$table)
                    <tr>
                        <td><input type="checkbox" name="tables[{{$key}}][]" value="{{$table}}"> {{$table}}</td>
                        <td>
                            <div>
                                <input type="hidden" id="status_1_" name="tables[{{$key}}][]"  value="0">
                                <input type="checkbox" name="tables[{{$key}}][]" value="0" id="insert_trigger"><label for="insert_trigger">Insert Trigger</label>
                                <input type="checkbox" name="tables[{{$key}}][]" value="0" id="update_trigger"><label for="update_trigger">Update Trigger</label>
                                <input type="checkbox" name="tables[{{$key}}][]" value="0" id="delete_trigger"><label for="delete_trigger">Delete Trigger</label>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
            <input type="submit">
        </form>
    </div>
@stop


