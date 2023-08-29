<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class ClearTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('SET session_replication_role = replica;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }
        \App\Models\Event::truncate();
        \App\Models\Inscrito::truncate();
        \DB::table('event_inscrito')->truncate();
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('SET session_replication_role = DEFAULT;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
        $this->info('Tables cleared!');
    }

}
