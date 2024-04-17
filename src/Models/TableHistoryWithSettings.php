<?php

namespace CodeStepsBD\HistoryKeeper\Models;

use Illuminate\Database\Eloquent\Model;

class TableHistoryWithSettings extends Model
{
    public $connection = "mysql";
    protected $table = "table_history_with_settings";
    protected $fillable = ['table_name'];
}
