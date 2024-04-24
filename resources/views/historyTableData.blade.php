@extends('historyKeeper::layouts')
@section('content')
<div class="container mt-3 table-responsive">
    <div class="row">
        <div class="col-md-6">
            <form action="" method="GET">
                <div class="row">
                    <div class="col-md-5 mb-4">
                        <select class="form-select form-select-md" id="select_table" name="table">
                            <option value="">Select Table</option>
                            @if($historyTableList)
                                @foreach($historyTableList as $item)
                                    <option {{ Request::get('table') == $item ? 'selected' : '' }} value="{{$item}}">{{$item}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-5 mb-4">
                        <select class="form-select form-select-md" id="per_page_item" name="per_page">
                            <option {{ Request::get('per_page') == 20 ? 'selected' : '' }} value="20">20</option>
                            <option {{ Request::get('per_page') == 50 ? 'selected' : '' }} value="50">50</option>
                            <option {{ Request::get('per_page') == 100 ? 'selected' : '' }} value="100">100</option>
                            <option {{ Request::get('per_page') == 200 ? 'selected' : '' }} value="200">200</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <form action="{{ route('history-table-data-delete') }}" method="post">
                <div class="row">
                    <input type="hidden" value="{{ Request::url() }}" name="url">
                    <div class="col-md-5 mb-4">
                        <div class='wrapper'>
                            <input class="form-select" type="text" id="from" name="from" placeholder="From Date">
                        </div>
                    </div>
                    <div class="col-md-5 mb-4">
                        <div class='wrapper'>
                            <input class="form-select" type="text" id="to" name="to" placeholder="To Date">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @if($dataList)
        <div class="row">
            <table class="table table-striped">
                <thead>
                <tr>
                    @foreach($columns as $column)
                        <th scope="col" width="1%">{{$column}}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @if($dataList->total() > 0)
                    @foreach($dataList as $item)

                        <tr>
                            @foreach($columns as $column)
                                <td>{{$item->{$column} }}</td>
                            @endforeach

                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="{{count($columns)}}">
                            <div class="alert alert-primary p-2 text-center" role="alert">
                                Data is not available
                            </div>
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
        <div class="d-flex">
            {!! $dataList->links() !!}
        </div>
    @endif
</div>
@push('custom-scripts')
    <script type="text/javascript">
        $(document).ready(function (){
            $( "#from" ).datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 1,
                onClose: function( selectedDate ) {
                    $( "#to" ).datepicker( "option", "minDate", selectedDate );
                }
            });
            $( "#to" ).datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 1,
                onClose: function( selectedDate ) {
                    $( "#from" ).datepicker( "option", "maxDate", selectedDate );
                }
            });
        })
    </script>
@endpush
@stop

