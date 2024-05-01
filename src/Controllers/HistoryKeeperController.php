<?php

namespace CodeStepsBD\HistoryKeeper\Controllers;

use App\Http\Controllers\Controller;
use CodeStepsBD\HistoryKeeper\Models\TableHistoryWithSettings;
use CodeStepsBD\HistoryKeeper\Repositories\HistoryKeeperRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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

        /**
         * @var $insertedTable Collection
         */
        $insertedTable = collect(TableHistoryWithSettings::get(['id', 'table_name', 'insert_trigger', 'update_trigger','delete_trigger'])->toArray());

        $excludeTableList = $insertedTable->pluck('table_name')->toArray();

        $newTableListNotUsedForHistory = $this->historyKeeperRepository->getTableList($excludeTableList);
        $newTableCollectionNotUsedForHistory = $newTableListNotUsedForHistory->map(fn($t) => ['table_name' => $t, 'insert_trigger'=>0, 'update_trigger'=>0, 'delete_trigger' =>0]);

        $insertedTable = $insertedTable->merge($newTableCollectionNotUsedForHistory);

        return view(view: "historyKeeper::configuration.create",data:['tableList'=>$insertedTable->sortBy('table_name')]);
    }

    public function store(Request $request)
    {
        $request->validate([
            "tablename" => "array",
            "tablename.*" => "required",
            "insert_trigger" => "array",
            "insert_trigger.*" => "required",
            "update_trigger" => "array",
            "update_trigger.*" => "required",
            "delete_trigger" => "array",
            "delete_trigger.*" => "required",
        ]);

        $tables = $request->input('tablename');

        TableHistoryWithSettings::truncate();

        foreach($tables as $key=>$tablename){

            $data = [
                'table_name' => $tablename,
                'insert_trigger' => $request->insert_trigger[$tablename],
                'update_trigger' => $request->update_trigger[$tablename],
                'delete_trigger' => $request->delete_trigger[$tablename],
            ];
            TableHistoryWithSettings::create($data);

        }
        return back()->with(['success'=>'your message,here']);
    }
    public function edit($id){
        $data = $this->historyKeeperRepository->edit($id);
        return view(view: "historyKeeper::configuration.edit",data:['data'=>$data]);
    }
    
    public function update(Request $request,$id){
        $data = $this->historyKeeperRepository->update($request,$id);
        return redirect()->route('history-keeper.configuration.edit',$id);
    }
}
