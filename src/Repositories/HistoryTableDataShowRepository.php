<?php

namespace CodeStepsBD\HistoryKeeper\Repositories;

use CodeStepsBD\HistoryKeeper\Models\TableHistoryWithSettings;
use Illuminate\Console\OutputStyle;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HistoryTableDataShowRepository
{
    public function getData($table,$per_page)
    {
        $data =  DB::table($table)->paginate($per_page);
        return $data->appends(request()->query());
    }

    public function getTableList(): Collection
    {
        $insertedTable = collect(TableHistoryWithSettings::get(['table_name', 'insert_trigger', 'update_trigger','delete_trigger'])->toArray());

        $excludeTableList = $insertedTable->pluck('table_name')->toArray();

        $tableNames = DB::table(DB::Raw('information_schema.tables'))
            ->select("table_name as table_name")
            ->whereRaw(" table_schema = 'package' AND table_type = 'BASE TABLE'")
            //->whereNotIn('table_name',[...$excludeTableList,  ...$this->getSkipTableList()])
            ->where('table_name','like','%_history')
            ->orwhereIn('table_name',[...$this->getMustAddTableList()])
            ->get()->pluck('table_name');
        return $tableNames;
    }

    private function getSkipTableList(): array {
        return config("historyKeeper.skipTables");
    }
    private function getMustAddTableList(): array {
        return config("historyKeeper.mustAddTables");
    }
}
