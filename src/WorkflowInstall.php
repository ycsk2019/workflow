<?php


namespace Ycsk\Definedform;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WorkflowInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lskstc:workflow-install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '导入自定义表单工作流数据库';

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
        $sql       = dirname(__DIR__) . '/sql/workflow_structure.sql';
        $model     = new \Ycsk\Definedform\Modules\Definedform\Models\Process();
        $tableName = $model->getTable();
        if (Schema::hasTable($tableName)) {
            dd("{$tableName}表已经存在");
        }
        $model     = new \Ycsk\Definedform\Modules\Definedform\Models\ProcessInstance();
        $tableName = $model->getTable();
        if (Schema::hasTable($tableName)) {
            dd("{$tableName}表已经存在");
        }
        $model     = new \Ycsk\Definedform\Modules\Definedform\Models\ProcessNode();
        $tableName = $model->getTable();
        if (Schema::hasTable($tableName)) {
            dd("{$tableName}表已经存在");
        }
        $model     = new \Ycsk\Definedform\Modules\Definedform\Models\ProcessNodeInstance();
        $tableName = $model->getTable();
        if (Schema::hasTable($tableName)) {
            dd("{$tableName}表已经存在");
        }
        $model     = new \Ycsk\Definedform\Modules\Definedform\Models\ProcessNodeLink();
        $tableName = $model->getTable();
        if (Schema::hasTable($tableName)) {
            dd("{$tableName}表已经存在");
        }
        DB::unprepared(file_get_contents($sql));
    }
}
