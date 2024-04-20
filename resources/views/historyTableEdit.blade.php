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
        <form method="post" action="{{ route('history.table.update',$data->id) }}">
            <table>
                    <tr>
                        <td><input type="checkbox" name="tables[table_name]" {{($data->table_name) ? "checked":""}} value="{{$data->table_name}}"> {{$data->table_name}}</td>
                        <td>
                            <div>
                                <input type="checkbox" name="tables[insert_trigger]" {{($data->insert_trigger == 1) ? "checked":""}} value="1" id="insert_trigger"><label for="insert_trigger">Insert Trigger</label>
                                <input type="checkbox" name="tables[update_trigger]" {{($data->update_trigger == 1) ? "checked":""}} value="1" id="update_trigger"><label for="update_trigger">Update Trigger</label>
                                <input type="checkbox" name="tables[delete_trigger]" {{($data->delete_trigger == 1) ? "checked":""}} value="1" id="delete_trigger"><label for="delete_trigger">Delete Trigger</label>
                            </div>
                        </td>
                    </tr>
            </table>
            <input type="submit">
        </form>
    </div>
@stop


