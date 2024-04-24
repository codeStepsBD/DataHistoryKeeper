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

class HistoryTableCommandUrlController extends Controller
{
    public $historyKeeperRepository;
    public function __construct(HistoryKeeperRepository $historyKeeperRepository)
    {
        $this->historyKeeperRepository = $historyKeeperRepository;
    }

    public function commandUrl($value=false)
    {
        if (!$value){
            return view(view: "historyKeeper::manual-command-run");
        }

        if ($value == 'runTest') {
            $this->historyKeeperRepository->runTest = true;
        }

        $makeNewHistoryTable = $value;
        if ($makeNewHistoryTable == 'true') {
            $this->historyKeeperRepository->dropExistHistoryTable = true;
        }

        if ($value == 'scanMismatch') {
            $this->historyKeeperRepository->scanMismatch = true;
        }
        $this->historyKeeperRepository->UpdateHistoryTableAndTrigger();
    }
}
