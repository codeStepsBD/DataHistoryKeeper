<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("table_history_with_settings", function (Blueprint $blueprint){
            $blueprint->id();
            $blueprint->string("table_name",200);
            $blueprint->tinyInteger("insert_trigger")->default(1);
            $blueprint->tinyInteger("update_trigger")->default(1);
            $blueprint->tinyInteger("delete_trigger")->default(1);
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_history_with_settings');
    }
};
