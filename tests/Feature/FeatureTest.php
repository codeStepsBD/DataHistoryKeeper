<?php

namespace CodeStepsBD\HistoryKeeper\Tests\Feature;

use CodeStepsBD\HistoryKeeper\Models\TableHistoryWithSettings;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class FeatureTest extends TestCase
{


    /**
     * A basic test example.
     */
    public function test_history_keeper_index_page_is_working(): void
    {
        $response = $this->get('/history-keeper');

        $response->assertStatus(200);
    }

    public function test_can_see_history_table_in_index_page(): void
    {

        $insertedTable = TableHistoryWithSettings::select(['table_name', 'insert_trigger', 'update_trigger','delete_trigger'])->first();

        $response = $this->get('/history-keeper');

        if ($insertedTable){
            $response->assertSee($insertedTable->table_name);
        }

        $response->assertStatus(200);
    }

    public function test_can_save_history_table(): void
    {

        $tableName = "test_table";
        $response = $this->post('/history-keeper/store',[
            'tablename' => ['test_table'],
            'insert_trigger' => ['test_table'=>1],
            'update_trigger' => ['test_table'=>1],
            'delete_trigger' => ['test_table'=>1],
        ]);
        $response->assertStatus(302);
        $count = TableHistoryWithSettings::select(['table_name', 'insert_trigger', 'update_trigger','delete_trigger'])
            ->where('table_name', $tableName)
            ->count();
        $this->assertEquals(1, $count);

    }
}
