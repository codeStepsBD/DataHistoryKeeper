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
                @foreach($tableList as $table )
                    @php(extract(['table' => $table['table_name'], 'insert_trigger'=>$table['insert_trigger'], 'update_trigger'=>$table['update_trigger'], 'delete_trigger' =>$table['delete_trigger']]))

                    <tr>
                        <td>
                            <input type="hidden" name="tablename[{{$table}}]" value="{{$table}}">
                            <label>{{$table}}</label> </td>
                        <td>
                            <div>
                                <input type="hidden" name="insert_trigger[{{$table}}]" value="0">
                                <input type="checkbox" name="insert_trigger[{{$table}}]" value="1" @if($insert_trigger==1)checked="checked" @endif id="insert_trigger_{{$table}}"><label for="insert_trigger_{{$table}}">Insert Trigger</label>


                                <input type="hidden" name="update_trigger[{{$table}}]" value="0">
                                <input type="checkbox" name="update_trigger[{{$table}}]" value="1"  @if($update_trigger==1)checked="checked" @endif  id="update_trigger_{{$table}}"><label for="update_trigger_{{$table}}">Update Trigger</label>



                                <input type="hidden" name="delete_trigger[{{$table}}]" value="0">
                                <input type="checkbox" name="delete_trigger[{{$table}}]" value="1"  @if($delete_trigger==1)checked="checked" @endif  id="delete_trigger_{{$table}}"><label for="delete_trigger_{{$table}}">Delete Trigger</label>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
            <input type="submit" value="Save Changes">
        </form>
    </div>
@stop


