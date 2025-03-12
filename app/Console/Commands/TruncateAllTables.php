<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

// This command is useful when restoring (production) database dumps.
class TruncateAllTables extends Command
{
    protected $signature = 'tables:truncate-all';
    protected $description = 'Truncates all tables in the database.';

    public function handle()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Get all tables (excluding views)
        $tables = DB::select("
        SELECT TABLE_NAME 
        FROM INFORMATION_SCHEMA.TABLES 
        WHERE TABLE_TYPE = 'BASE TABLE' 
        AND TABLE_SCHEMA = DATABASE()
        ");

        foreach ($tables as $table) {
            $tableName = $table->TABLE_NAME;

            try {
                DB::table($tableName)->truncate();
                $this->info("Table {$tableName} truncated.");
            } catch (\Exception $e) {
                $this->error("Skipped {$tableName} (not a table or does not exist)");
            }
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
