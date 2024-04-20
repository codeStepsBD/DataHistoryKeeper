<?php

namespace CodeStepsBD\HistoryKeeper\Controllers;

use App\Http\Controllers\Controller;
use CodeStepsBD\HistoryKeeper\Models\TableHistoryWithSettings;
use CodeStepsBD\HistoryKeeper\Repositories\HistoryKeeperRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

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
        $request->validate([
            "tables" => "required|array",
            "tables.*" => "required|array",
        ]);

        $tables = $request->input('tables');
        foreach($tables as $table){
            $data = [
                'table_name' => $table['table_name'],
                'insert_trigger' => $table['insert_trigger'] ?? 0,
                'update_trigger' => $table['update_trigger'] ?? 0,
                'delete_trigger' => $table['delete_trigger'] ?? 0,
            ];
            TableHistoryWithSettings::create($data);
        }
        return redirect()->back()->with('success', 'your message,here');
    }
}
