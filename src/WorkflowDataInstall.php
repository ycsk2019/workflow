<?php
namespace Ycsk\Definedform;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WorkflowDataInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lskstc:workflowdata-install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '导入自定义表单工作流数据库数据';

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
        $sql       = dirname(__DIR__) . '/sql/workflow_data.sql';
        DB::unprepared(file_get_contents($sql));
    }
}