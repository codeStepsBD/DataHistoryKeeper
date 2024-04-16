<?php

namespace CodeStepsBD\HistoryKeeper\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HistoryKeeperController extends Controller
{
    public function index(Request $request){
        return view(view: "historyKeeper::content");
    }
}
