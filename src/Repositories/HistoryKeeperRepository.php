<?php

namespace CodeStepsBD\HistoryKeeper\Repositories;

use CodeStepsBD\HistoryKeeper\Models\TableHistoryWithSettings;
use Illuminate\Console\OutputStyle;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Termwind\Components\Raw;

class HistoryKeeperRepository
{

    protected array $mysql_reserved_key = ['group', 'order', 'char', 'date', 'group', 'char', 'time', 'order', 'clob', 'insert', 'where', 'from'];
    public bool $dropExistHistoryTable = false;
    public bool $scanMismatch = false;
    public bool $runTest = false;

    public OutputStyle $output;

    public $mysql_table_schema = null;
    public $allow_hist_table = [];


    public function UpdateHistoryTableAndTrigger()
    {

        $allow_hist_table = TableHistoryWithSettings::get()->toArray();
        $this->allow_hist_table = collect($allow_hist_table);
        $this->initDBOwnerOrSchema();
        if ($this->runTest) {
            $this->generateTestDataAndTestHistoryTable();
            return;
        }

        $progressBar = $this->output->createProgressBar($this->allow_hist_table->count());

        $this->calling_from_command = true;

        foreach ($this->allow_hist_table as $table) {
            $this->processTable((array)$table);
            $progressBar->advance();
        }
        $progressBar->finish();
        echo ("\n Congratulations! You have successfully done it");
    }

    function processTable(array $table)
    {
        $historyTableNewlyCreated = false;

        $baseTable = $table["table_name"];
        $historyTable = $table["table_name"]. "_history";
        if ($this->dropExistHistoryTable && Schema::hasTable($historyTable)) {
            Schema::drop($historyTable);
        }

        if (!Schema::hasTable($historyTable)) {
            $this->createTable($historyTable, $baseTable);
            $historyTableNewlyCreated = true;
        }


        $mainTableColumns = array_filter(Schema::getColumnListing($baseTable), fn($t) => !str_contains($t, '$') ? $t : null);

        $main_tbl_info = $this->getColumnDefinition($baseTable);
        $his_tbl_info = $this->getColumnDefinition($historyTable);

        $main_tbl_col = array_column($main_tbl_info, 'column_name');
        $his_tbl_col = array_column($his_tbl_info, 'column_name');

        $main_tbl_differences = array_diff($main_tbl_col, $his_tbl_col);
        $his_tbl_differences = array_diff($his_tbl_col, $main_tbl_col);

        if (count($main_tbl_differences) > 0 && $this->dropExistHistoryTable == false && $historyTableNewlyCreated == false) {
            if ($this->scanMismatch == false) {
                $this->addMissingColumnInHistoryTable($baseTable, $main_tbl_differences);
            } else {
                echo "Column mismatch found between <strong>$baseTable</strong> and <strong>$historyTable</strong>. " . PHP_EOL;
                echo "Missing column(s) in <strong>$historyTable</strong>  -  <span style='color: red;'>" . implode(", ", $main_tbl_differences) ."</span>" . PHP_EOL . PHP_EOL;
            }
        }

        if (count($his_tbl_differences) > 2 && $this->dropExistHistoryTable == false && $historyTableNewlyCreated == false) {
            echo "Column mismatch found between $baseTable and $historyTable. $historyTable table has -  " . implode(", ", $his_tbl_differences) . "\n\n";
        }

        if (!$this->scanMismatch) {
            if ($table["insert_trigger"] == 1) {
                $this->createOrUpdateInsertTrigger($baseTable, $historyTable);
            }

            if ($table["update_trigger"] == 1) {
                $this->createOrUpdateUpdateTrigger($baseTable, $historyTable);
            }

            if ($table["delete_trigger"] == 1) {
                $this->createOrUpdateDeleteTrigger($baseTable, $historyTable);
            }
        }

    }

    private function createTable(string $historyTable, string $baseTable)
    {
        DB::statement("CREATE TABLE $historyTable
           AS SELECT * FROM $baseTable WHERE 1=0");

        DB::statement("ALTER TABLE $historyTable
                ADD COLUMN (
                    history_updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    action_history VARCHAR(30)
                )"
        );

        echo("\n table $historyTable created");
    }


    private function createOrUpdateInsertTrigger($baseTableName, $historyTableName)
    {
            DB::unprepared("DROP TRIGGER IF EXISTS {$baseTableName}_his_on_ins");
            $pl = ("
            CREATE TRIGGER {$baseTableName}_his_on_ins AFTER INSERT ON {$baseTableName}
            FOR EACH ROW
            BEGIN
                INSERT INTO {$historyTableName}
                SELECT *, CURRENT_TIMESTAMP, 'Inserted' FROM {$baseTableName} WHERE id = NEW.id;
            END
        ");
        DB::statement($pl);
    }

    private function createOrUpdateUpdateTrigger($baseTableName, $historyTableName)
    {

        DB::unprepared("DROP TRIGGER IF EXISTS {$baseTableName}_his_on_update");
        $pl = "
            CREATE TRIGGER {$baseTableName}_his_on_update BEFORE UPDATE ON {$baseTableName}
            FOR EACH ROW
            BEGIN
                INSERT INTO {$historyTableName}
                SELECT *, CURRENT_TIMESTAMP, 'Updated' FROM {$baseTableName} WHERE id = NEW.id;
            END
        ";
        DB::statement($pl);
    }

    private function createOrUpdateDeleteTrigger($baseTableName, $historyTableName)
    {
        DB::unprepared("DROP TRIGGER IF EXISTS {$baseTableName}_his_on_del");
        $pl = ("
            CREATE TRIGGER {$baseTableName}_his_on_del BEFORE DELETE ON {$baseTableName}
            FOR EACH ROW
            BEGIN
                INSERT INTO {$historyTableName}
                SELECT *, CURRENT_TIMESTAMP, 'Delete' FROM {$baseTableName} WHERE id = OLD.id;
            END
        ");

        DB::statement($pl);
    }


    private function getColumnDefinition($tableName, $columnName = null)
    {
        $sql = "SELECT
                lower(column_name) column_name,
                lower(data_type) data_type,
                character_maximum_length as data_length,
                numeric_precision as data_precision,
                numeric_scale as data_scale,
                CASE is_nullable
                        when 'YES' then 'Y'
                        else 'N' end as nullable,
                column_default as data_default,
                lower(column_type) column_type
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE lower(TABLE_NAME) = lower('$tableName') and lower(table_schema) = lower('" . $this->mysql_table_schema . "') ";
        if ($columnName) {
            $sql .= " and lower(column_name) = lower('$columnName') ";
        }
        $sql .= "ORDER BY ordinal_position ASC";
        #echo $sql;
        return DB::select($sql);
    }


    public function initDBOwnerOrSchema()
    {
        $this->mysql_table_schema = env('DB_DATABASE');
    }

    public function getMySqlTableColumnsDesc(string $tableName, array $columnsName)
    {

        /**
         * SELECT
         * COLUMN_NAME, COLUMN_TYPE, COLUMN_DEFAULT, IS_NULLABLE, ORDINAL_POSITION
         * FROM information_schema.COLUMNS where TABLE_NAME = 'invoices' and TABLE_SCHEMA = 'indianbank'
         */

        $columnsName = collect($columnsName)->map(function ($f) {
            return "'" . strtolower($f) . "'";
        })->toArray();
        $dbColNames = implode(',', $columnsName);

        $cols = DB::table(DB::raw('information_schema.COLUMNS'))
            ->selectRaw("column_name column_name, column_type column_type, column_default column_default, is_nullable is_nullable, ordinal_position ordinal_position")
            ->selectRaw("(SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS AS c2 WHERE lower(c2.table_name) = lower('$tableName') AND c2.ordinal_position = information_schema.COLUMNS.ordinal_position - 1) AS prev_column")
            ->whereRaw("lower(table_name) = lower('$tableName')")
            ->whereRaw("lower(column_name) in ($dbColNames)")
            ->get();

        if ($cols->count() == 0) {
            echo "$dbColNames not found in table- `$tableName` ";
            exit(0);
        }
        return $cols;
    }


    function addMissingColumnInHistoryTable($baseTable, $missingColumnList)
    {
        $this->addColumnInMySql($baseTable, $missingColumnList);
    }

    function addColumnInMySql($baseTable, $missingColumnList)
    {
        $colsDesc = $this->getMySqlTableColumnsDesc($baseTable, $missingColumnList);
        $tableConfig = $this->allow_hist_table->where("base_table", $baseTable)->first();

        if (!isset($tableConfig) || !isset($tableConfig['his_table'])) {
            echo "$baseTable or his_table in array config not found.";
            exit(0);
        }
        $historyTableName = $tableConfig['his_table'];

        foreach ($colsDesc as $colDesc) {

            $baseColumnName = $this->handleMySqlKeywordAsColumn($colDesc->column_name);
            $prevColumn = $this->handleMySqlKeywordAsColumn($colDesc->prev_column);
            $baseColumnType = strtoupper($colDesc->column_type);

            $default = $colDesc->column_default !== null ? " DEFAULT '" . $colDesc->column_default . "'" : "";

            $sql = "ALTER TABLE $historyTableName ADD COLUMN $baseColumnName $baseColumnType $default  NULL AFTER $prevColumn";

            echo "executing for adding missing column - " . $sql . "\n";
            DB::statement($sql);
        }
    }


    public function generateTestDataAndTestHistoryTable()
    {


        echo " Trigger checking only for Attachments table\n";

        $tableArray = $this->allow_hist_table->where('base_table', 'attachments')->first();

        $baseTable = $tableArray['base_table'];
        $hisTable = $tableArray['his_table'];
        $need_insert_tr = $tableArray['need_insert_tr'];
        $need_update_tr = $tableArray['need_update_tr'];
        $need_delete_tr = $tableArray['need_delete_tr'];

        $colsDesc = $this->getColumnDefinition($baseTable);
//        dd($colsDesc);
        $baseTableInsertArray = [];
        foreach ($colsDesc as $colDesc) {
            if (strtolower($colDesc->column_name) == 'id') {
                continue;
            }
            $dataByType = $this->getData($colDesc);
            $baseTableInsertArray[$colDesc->column_name] = $dataByType;
        }

        DB::beginTransaction();
        //dd($baseTableInsertArray, DB::table($baseTable)->latest('id')->first());
        DB::table($baseTable)->insert($baseTableInsertArray);
        $id = DB::table($baseTable)->latest('id')->first()->id;
        DB::table($baseTable)->where(['id' => $id])->update(['created_at' => now()]);
        DB::table($baseTable)->where(['id' => $id])->delete();
        $count = DB::table($hisTable)->where(['id' => $id])->count();
        DB::rollBack();

        echo $baseTable . '=>' . $hisTable .
            " \n operation for insert,update and delete \n";
        echo $baseTable . '=>' . $hisTable . '=> triggers:' . $need_insert_tr . $need_update_tr . $need_delete_tr . ", history table data found:" . $count . "\n";
        //dd(substr_count($need_insert_tr . $need_update_tr . $need_delete_tr,'Y'));
        if (substr_count($need_insert_tr . $need_update_tr . $need_delete_tr, 'Y') != 3) {
            $this->warn("history table has not proper data. maybe trigger is not working properly");
        }
    }

    public function getData($colDesc)
    {
        if (in_array(strtolower($colDesc->column_name), ['created_by', 'deleted_by'])) {
            return 1;
        }

        /*bigint, varchar, timestamp,int,longtext,enum,text,mediumtext,json,datetime,set,binary
        char,varbinary,tinyint,blob,double,decimal,longblob,smallint,mediumblob,time,float,date*/
        $dataTypeWithValue = array(
            'bigint' => 123456789012345,
            'varchar' => 'Hello, World!',
            'varchar2' => 'Hello, World!',
            'timestamp' => '2024-04-09 15:30:00',
            'int' => 123,
            'longtext' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'enum' => 'option1',
            'text' => 'Some text data.',
            'mediumtext' => 'Medium-sized text data.',
            'json' => '{"key": "value"}',
            'datetime' => '2024-04-09 15:30:00',
            'set' => 'option1, option2',
            'binary' => 'binary data',
            'char' => 'A',
            'varbinary' => 'binary data',
            'tinyint' => 1,
            'blob' => 'binary large object',
            'double' => 123.45,
            'decimal' => '123.45',
            'number' => '123.45',
            'longblob' => 'binary large object',
            'smallint' => 123,
            'mediumblob' => 'binary large object',
            'time' => '15:30:00',
            'float' => 123.45,
            'date' => '2024-04-09',
            'timestamp(6)' => '2024-04-09 15:30:00',
        );
        return $dataTypeWithValue[strtolower($colDesc->data_type)];
    }

    function handleMySqlKeywordAsColumn($columnName)
    {

        $columnName = strtolower($columnName);
        if (in_array($columnName, $this->mysql_reserved_key)) {
            return "`$columnName`";
        }
        return $columnName;

    }

    public function getTableList(): Collection
    {

        $this->initDBOwnerOrSchema();

        $tableNames = DB::table(DB::Raw('information_schema.tables'))
            ->select("table_name as table_name")
            ->whereRaw(" table_schema = '".$this->mysql_table_schema."' AND table_type = 'BASE TABLE'")->get()->pluck('table_name');
        return $tableNames;
    }
}
