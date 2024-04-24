<?php

namespace CodeStepsBD\HistoryKeeper\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use CodeStepsBD\HistoryKeeper\Models\TableHistoryWithSettings;
use CodeStepsBD\HistoryKeeper\Repositories\HistoryKeeperRepository;
use CodeStepsBD\HistoryKeeper\Repositories\HistoryTableDataShowRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Input\Input;

class HistoryTableDataShowController extends Controller
{
    public $historyTableDataShow;
    public function __construct(HistoryTableDataShowRepository $historyTableDataShow)
    {
        $this->historyTableDataShow = $historyTableDataShow;
    }

    public function index(Request $request)
    {
       $table =  $request->input("table");
       $per_page =  $request->input("per_page");
        $historyTables = $this->historyTableDataShow->getTableList();
        if (!$table){
            $columns = [];
            $data = [];
        }else{
            $columns = Schema::getColumnListing($table);
            $data = $this->historyTableDataShow->getData($table,$per_page);
        }
        return view('historyKeeper::historyTableData',data:['dataList'=>$data,'columns'=>$columns, 'historyTableList'=>$historyTables]);
    }

    public function delete(Request $request)
    {
        $from = Carbon::parse($request->input("from"))->format('Y-m-d 00:00:00');
        $to =  Carbon::parse($request->input("to"))->format('Y-m-d 00:00:00');
        $result = DB::table('student_summary_history')->whereBetween('history_updated_at', [$from, $to])->delete();
        return back();
    }
}
