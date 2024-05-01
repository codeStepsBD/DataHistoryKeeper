@extends('historyKeeper::layouts')
@section('content')
<h1 class="h4">Configuration History Keeper</h1>
<form method="post" action="{{ route('history-keeper.configuration.store') }}">
    <table class="table">
        <tr>
            <th>Table Name</th>
            <th colspan="3">Triggers</th>
            <th class="text-end">Action</th>
        </tr>
        @foreach($tableList as $table)
            @php(extract(['table_name' => $table['table_name'], 'insert_trigger'=>$table['insert_trigger'], 'update_trigger'=>$table['update_trigger'], 'delete_trigger' =>$table['delete_trigger']]))
            <tr>
                <td>
                    <input type="hidden" name="tablename[{{$table_name}}]" value="{{$table_name}}">
                    <label>{{$table_name}}</label>
                </td>
                <td>
                    <div class="form-check form-check-inline">
                        <input type="hidden" name="insert_trigger[{{$table_name}}]" value="0">
                        <input type="checkbox" name="insert_trigger[{{$table_name}}]" value="1" @if($insert_trigger==1)checked="checked" @endif id="insert_trigger_{{$table_name}}" class="form-check-input">
                        <label class="form-check-label" for="insert_trigger_{{$table_name}}">Insert Trigger</label>
                    </div>
                </td>
                <td>
                    <div class="form-check form-check-inline">
                        <input type="hidden" name="update_trigger[{{$table_name}}]" value="0">
                        <input type="checkbox" name="update_trigger[{{$table_name}}]" value="1" @if($update_trigger==1)checked="checked" @endif id="update_trigger{{$table_name}}" class="form-check-input">
                        <label class="form-check-label" for="update_trigger{{$table_name}}">Update Trigger</label>
                    </div>
                </td>
                <td>
                    <div class="form-check form-check-inline">
                        <input type="hidden" name="delete_trigger[{{$table_name}}]" value="0">
                        <input type="checkbox" name="delete_trigger[{{$table_name}}]" value="1" @if($delete_trigger==1)checked="checked" @endif id="delete_trigger_{{$table_name}}" class="form-check-input">
                        <label class="form-check-label" for="delete_trigger_{{$table_name}}">Insert Trigger</label>
                    </div>
                </td>
                <td class="text-end">
                    <a href="{{ route('history-keeper.configuration.edit', $table['id']) }}" class="btn btn-info">Edit</a>
                </td>
            </tr>
        @endforeach
    </table>
    <input type="submit" class="btn btn-success" value="Save Changes">
</form>
@stop


