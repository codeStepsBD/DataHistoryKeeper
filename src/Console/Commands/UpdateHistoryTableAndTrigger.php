<?php

namespace CodeStepsBD\HistoryKeeper\Console\Commands;

use CodeStepsBD\HistoryKeeper\Repositories\HistoryKeeperRepository;
use Illuminate\Console\Command;

class UpdateHistoryTableAndTrigger extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-history-tables-and-triggers {--makeNewHistoryTable=false} {--runTest} {--scanMismatch}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will update history tables and triggers. Also check mismatch of base table and history table';



    /**
     * Execute the console command.
     */
    public function handle()
    {

        $historyKeeperRepo = new HistoryKeeperRepository();
        $historyKeeperRepo->output = $this->output;

        if ($this->option('runTest')) {
            $historyKeeperRepo->runTest = true;
        }

        if ($this->option('makeNewHistoryTable')=='true') {
            $historyKeeperRepo->dropExistHistoryTable = true;
        }

        if ($this->option('scanMismatch')) {
            $historyKeeperRepo->scanMismatch = true;
        }

        $historyKeeperRepo->UpdateHistoryTableAndTrigger();
    }
}
