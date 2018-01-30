<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeletes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'myevent:add_soft_deletes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add Soft Deletes to tables';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $tables = DB::select('SHOW TABLES');
            foreach($tables as $table)
            {
                  $table_name =  $table->{'Tables_in_'.env('DB_DATABASE')};
                  //dd($table);

                  if(!Schema::hasColumn($table_name, 'deleted_at')){
                        if($table_name!='migrations'){
                            Schema::table($table_name, function($t){
                                $t->string('deleted_at')->nullable();
                            });
                        }
                  }
            }

    }
}
