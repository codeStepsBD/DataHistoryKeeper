<?php

namespace CodeStepsBD\HistoryKeeper\Controllers;

use App\Http\Controllers\Controller;
use CodeStepsBD\HistoryKeeper\Models\TableHistoryWithSettings;
use CodeStepsBD\HistoryKeeper\Repositories\HistoryKeeperRepository;
use Illuminate\Http\Request;

class HistoryKeeperController extends Controller
{
    public $historyKeeperRepository;
    public function __construct(HistoryKeeperRepository $historyKeeperRepository)
    {
        $this->historyKeeperRepository = $historyKeeperRepository;
    }

    public function index(Request $request){
        $tableList = $this->historyKeeperRepository->getTableList();
        return view(view: "historyKeeper::content",data:['tableList'=>$tableList]);
    }

    public function store(Request $request)
    {
        $tables = $request->input('tables');
        foreach($tables as $table){
            $data = [
                'table_name' => $table,
            ];
            TableHistoryWithSettings::create($data);
        }
        return redirect()->back()->with('success', 'your message,here');
    }
}
