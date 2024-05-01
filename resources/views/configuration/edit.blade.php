@extends('historyKeeper::layouts')
@section('content')
<h1 class="h4">Update Configuration</h1>
<form method="post" action="{{ route('history-keeper.configuration.update',$data->id) }}">
    <table class="table">
        <tr>
            <th>Table Name</th>
            <th colspan="3">Triggers</th>
            <th class="text-end">Action</th>
        </tr>
            <tr>
                <td>
                    <div class="form-check form-check-inline">
                    <input type="checkbox" id="{{ $data->table_name }}" name="tables[table_name]" {{($data->table_name) ? "checked":""}} value="{{$data->table_name}}" class="form-check-input">
                    <label class="form-check-label" for="{{ $data->table_name }}">{{$data->table_name}}</label>
                </td>
                <td>
                    <div class="form-check form-check-inline">
                        <input type="checkbox" name="tables[insert_trigger]" {{($data->insert_trigger == 1) ? "checked":""}} value="1" id="insert_trigger" class="form-check-input">
                        <label class="form-check-label" for="insert_trigger">Insert Trigger</label>
                    </div>
                </td>
                <td>
                    <div class="form-check form-check-inline">
                        <input type="checkbox" name="tables[update_trigger]" {{($data->update_trigger == 1) ? "checked":""}} value="1" id="update_trigger" class="form-check-input">
                        <label class="form-check-label" for="update_trigger">Update Trigger</label>
                    </div>
                </td>
                <td>
                    <div class="form-check form-check-inline">
                        <input type="checkbox" name="tables[delete_trigger]" {{($data->delete_trigger == 1) ? "checked":""}} value="1" id="delete_trigger" class="form-check-input">
                        <label class="form-check-label" for="delete_trigger">Delete Trigger</label>
                    </div>
                </td>
                <td class="text-end">
                    <a href="{{ route('history-keeper.configuration.edit', $data->id) }}" class="btn btn-danger">Delete</a>
                </td>
            </tr>
    </table>
    <input type="submit" class="btn btn-success" value="Save Changes">
</form>
@stop


