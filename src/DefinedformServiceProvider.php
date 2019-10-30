<?php
namespace Ycsk\Definedform;
use Illuminate\Support\ServiceProvider;

class DefinedformServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'workflow');
        $this->commands([
            DefinedformInstall::class,
            DefinedformDataInstall::class,
            WorkflowInstall::class,
            WorkflowDataInstall::class
        ]);

        $this->publishes([
            __DIR__.'/Modules/Definedform/copy/Models/FormFormat.php' => app_path('Modules/Definedform/Models/FormFormat.php'),
            __DIR__.'/Modules/Definedform/copy/Controllers/FormFormatController.php' => app_path('Modules/Definedform/Controllers/FormFormatController.php'),
            __DIR__.'/Modules/Definedform/copy/Repositories/FormFormatRepositoryInterface.php' => app_path('Modules/Definedform/Repositories/FormFormatRepositoryInterface.php'),
            __DIR__.'/Modules/Definedform/copy/Repositories/FormFormatRepository.php' => app_path('Modules/Definedform/Repositories/FormFormatRepository.php'),
            __DIR__.'/Modules/Definedform/copy/Services/FormFormatServiceInterface.php' => app_path('Modules/Definedform/Services/FormFormatServiceInterface.php'),
            __DIR__.'/Modules/Definedform/copy/Services/FormFormatService.php' => app_path('Modules/Definedform/Services/FormFormatService.php'),
            __DIR__.'/Modules/Definedform/copy/Models/FormField.php' => app_path('Modules/Definedform/Models/FormField.php'),
            __DIR__.'/Modules/Definedform/copy/Controllers/FormFieldController.php' => app_path('Modules/Definedform/Controllers/FormFieldController.php'),
            __DIR__.'/Modules/Definedform/copy/Repositories/FormFieldRepositoryInterface.php' => app_path('Modules/Definedform/Repositories/FormFieldRepositoryInterface.php'),
            __DIR__.'/Modules/Definedform/copy/Repositories/FormFieldRepository.php' => app_path('Modules/Definedform/Repositories/FormFieldRepository.php'),
            __DIR__.'/Modules/Definedform/copy/Services/FormFieldServiceInterface.php' => app_path('Modules/Definedform/Services/FormFieldServiceInterface.php'),
            __DIR__.'/Modules/Definedform/copy/Services/FormFieldService.php' => app_path('Modules/Definedform/Services/FormFieldService.php'),
            __DIR__.'/Modules/Definedform/copy/Models/FormList.php' => app_path('Modules/Definedform/Models/FormList.php'),
            __DIR__.'/Modules/Definedform/copy/Controllers/FormListController.php' => app_path('Modules/Definedform/Controllers/FormListController.php'),
            __DIR__.'/Modules/Definedform/copy/Repositories/FormListRepositoryInterface.php' => app_path('Modules/Definedform/Repositories/FormListRepositoryInterface.php'),
            __DIR__.'/Modules/Definedform/copy/Repositories/FormListRepository.php' => app_path('Modules/Definedform/Repositories/FormListRepository.php'),
            __DIR__.'/Modules/Definedform/copy/Services/FormListServiceInterface.php' => app_path('Modules/Definedform/Services/FormListServiceInterface.php'),
            __DIR__.'/Modules/Definedform/copy/Services/FormListService.php' => app_path('Modules/Definedform/Services/FormListService.php'),
            __DIR__.'/Modules/Definedform/copy/Models/FormLog.php' => app_path('Modules/Definedform/Models/FormLog.php'),
            __DIR__.'/Modules/Definedform/copy/Repositories/FormLogRepositoryInterface.php' => app_path('Modules/Definedform/Repositories/FormLogRepositoryInterface.php'),
            __DIR__.'/Modules/Definedform/copy/Repositories/FormLogRepository.php' => app_path('Modules/Definedform/Repositories/FormLogRepository.php'),
            __DIR__.'/Modules/Definedform/copy/Services/FormLogServiceInterface.php' => app_path('Modules/Definedform/Services/FormLogServiceInterface.php'),
            __DIR__.'/Modules/Definedform/copy/Services/FormLogService.php' => app_path('Modules/Definedform/Services/FormLogService.php'),
            __DIR__.'/Modules/Definedform/copy/Models/FormMenu.php' => app_path('Modules/Definedform/Models/FormMenu.php'),
            __DIR__.'/Modules/Definedform/copy/Controllers/FormMenuController.php' => app_path('Modules/Definedform/Controllers/FormMenuController.php'),
            __DIR__.'/Modules/Definedform/copy/Repositories/FormMenuRepositoryInterface.php' => app_path('Modules/Definedform/Repositories/FormMenuRepositoryInterface.php'),
            __DIR__.'/Modules/Definedform/copy/Repositories/FormMenuRepository.php' => app_path('Modules/Definedform/Repositories/FormMenuRepository.php'),
            __DIR__.'/Modules/Definedform/copy/Services/FormMenuServiceInterface.php' => app_path('Modules/Definedform/Services/FormMenuServiceInterface.php'),
            __DIR__.'/Modules/Definedform/copy/Services/FormMenuService.php' => app_path('Modules/Definedform/Services/FormMenuService.php'),
            __DIR__.'/Modules/Definedform/copy/Models/FormListHead.php' => app_path('Modules/Definedform/Models/FormListHead.php'),
            __DIR__.'/Modules/Definedform/copy/Controllers/FormListHeadController.php' => app_path('Modules/Definedform/Controllers/FormListHeadController.php'),
            __DIR__.'/Modules/Definedform/copy/Repositories/FormListHeadRepositoryInterface.php' => app_path('Modules/Definedform/Repositories/FormListHeadRepositoryInterface.php'),
            __DIR__.'/Modules/Definedform/copy/Repositories/FormListHeadRepository.php' => app_path('Modules/Definedform/Repositories/FormListHeadRepository.php'),
            __DIR__.'/Modules/Definedform/copy/Services/FormListHeadServiceInterface.php' => app_path('Modules/Definedform/Services/FormListHeadServiceInterface.php'),
            __DIR__.'/Modules/Definedform/copy/Services/FormListHeadService.php' => app_path('Modules/Definedform/Services/FormListHeadService.php'),
            __DIR__.'/Modules/Definedform/copy/Models/FormMenuProcess.php' => app_path('Modules/Definedform/Models/FormMenuProcess.php'),
            __DIR__.'/Modules/Definedform/copy/Models/Process.php' => app_path('Modules/Definedform/Models/Process.php'),
            __DIR__.'/Modules/Definedform/copy/Models/Order.php' => app_path('Modules/Definedform/Models/Order.php'),
            __DIR__.'/Modules/Definedform/copy/Controllers/OrderController.php' => app_path('Modules/Definedform/Controllers/OrderController.php'),
            __DIR__.'/Modules/Definedform/copy/Repositories/OrderRepositoryInterface.php' => app_path('Modules/Definedform/Repositories/OrderRepositoryInterface.php'),
            __DIR__.'/Modules/Definedform/copy/Repositories/OrderRepository.php' => app_path('Modules/Definedform/Repositories/OrderRepository.php'),
            __DIR__.'/Modules/Definedform/copy/Services/OrderServiceInterface.php' => app_path('Modules/Definedform/Services/OrderServiceInterface.php'),
            __DIR__.'/Modules/Definedform/copy/Services/OrderService.php' => app_path('Modules/Definedform/Services/OrderService.php'),
            __DIR__.'/Modules/Definedform/copy/Helpers/ApiResponse.php' => app_path('Modules/Definedform/Helpers/ApiResponse.php'),
            __DIR__.'/Modules/Definedform/copy/Helpers/Util.php' => app_path('Modules/Definedform/Helpers/Util.php'),
            __DIR__.'/Modules/Definedform/copy/Controllers/Controller.php' => app_path('Modules/Definedform/Controllers/Controller.php'),
            __DIR__.'/Modules/Definedform/copy/Routes/definedform.php' => base_path('routes/definedform.php'),
            __DIR__.'/Modules/Definedform/copy/Models/FormFormatFormList.php' => app_path('Modules/Definedform/Models/FormFormatFormList.php'),
            __DIR__.'/Modules/Definedform/copy/Models/FormSystemField.php' => app_path('Modules/Definedform/Models/FormSystemField.php'),
            __DIR__.'/Modules/Definedform/copy/Models/ProcessInstance.php' => app_path('Modules/Definedform/Models/ProcessInstance.php'),
            __DIR__.'/Modules/Definedform/copy/Models/ProcessNode.php' => app_path('Modules/Definedform/Models/ProcessNode.php'),
            __DIR__.'/Modules/Definedform/copy/Models/ProcessNodeInstance.php' => app_path('Modules/Definedform/Models/ProcessNodeInstance.php'),
            __DIR__.'/Modules/Definedform/copy/Models/ProcessNodeLink.php' => app_path('Modules/Definedform/Models/ProcessNodeLink.php'),
            __DIR__.'/Modules/Definedform/copy/Models/ProcessTask.php' => app_path('Modules/Definedform/Models/ProcessTask.php'),
            __DIR__.'/Modules/Definedform/copy/Repositories/WorkflowRepositoryInterface.php' => app_path('Modules/Definedform/Repositories/WorkflowRepositoryInterface.php'),
            __DIR__.'/Modules/Definedform/copy/Repositories/WorkflowRepository.php' => app_path('Modules/Definedform/Repositories/WorkflowRepository.php'),
            __DIR__.'/Modules/Definedform/copy/Services/WorkflowServiceInterface.php' => app_path('Modules/Definedform/Services/WorkflowServiceInterface.php'),
            __DIR__.'/Modules/Definedform/copy/Services/WorkflowService.php' => app_path('Modules/Definedform/Services/WorkflowService.php'),
            __DIR__ . '/resources/assets' => public_path('vendor/lskstc/definedform'),
        ], 'definedform');
    }

    public function register()
    {
        $this->app->singleton(\App\Modules\Definedform\Services\FormFormatServiceInterface::class,\App\Modules\Definedform\Services\FormFormatService::class);
        $this->app->singleton(\App\Modules\Definedform\Repositories\FormFormatRepositoryInterface::class,\App\Modules\Definedform\Repositories\FormFormatRepository::class);
        $this->app->singleton(\App\Modules\Definedform\Services\FormFieldServiceInterface::class,\App\Modules\Definedform\Services\FormFieldService::class);
        $this->app->singleton(\App\Modules\Definedform\Repositories\FormFieldRepositoryInterface::class,\App\Modules\Definedform\Repositories\FormFieldRepository::class);
        $this->app->singleton(\App\Modules\Definedform\Services\FormListServiceInterface::class,\App\Modules\Definedform\Services\FormListService::class);
        $this->app->singleton(\App\Modules\Definedform\Repositories\FormListRepositoryInterface::class,\App\Modules\Definedform\Repositories\FormListRepository::class);
        $this->app->singleton(\App\Modules\Definedform\Services\FormLogServiceInterface::class,\App\Modules\Definedform\Services\FormLogService::class);
        $this->app->singleton(\App\Modules\Definedform\Repositories\FormLogRepositoryInterface::class,\App\Modules\Definedform\Repositories\FormLogRepository::class);
        $this->app->singleton(\App\Modules\Definedform\Services\FormMenuServiceInterface::class,\App\Modules\Definedform\Services\FormMenuService::class);
        $this->app->singleton(\App\Modules\Definedform\Repositories\FormMenuRepositoryInterface::class,\App\Modules\Definedform\Repositories\FormMenuRepository::class);
        $this->app->singleton(\App\Modules\Definedform\Services\FormListHeadServiceInterface::class,\App\Modules\Definedform\Services\FormListHeadService::class);
        $this->app->singleton(\App\Modules\Definedform\Repositories\FormListHeadRepositoryInterface::class,\App\Modules\Definedform\Repositories\FormListHeadRepository::class);
        $this->app->singleton(\App\Modules\Definedform\Services\OrderServiceInterface::class,\App\Modules\Definedform\Services\OrderService::class);
        $this->app->singleton(\App\Modules\Definedform\Repositories\OrderRepositoryInterface::class,\App\Modules\Definedform\Repositories\OrderRepository::class);
        $this->app->singleton(\App\Modules\Definedform\Services\WorkflowServiceInterface::class,\App\Modules\Definedform\Services\WorkflowService::class);
        $this->app->singleton(\App\Modules\Definedform\Repositories\WorkflowRepositoryInterface::class,\App\Modules\Definedform\Repositories\WorkflowRepository::class);
        $this->app->singleton(\App\Modules\Definedform\Services\FormPresetFieldServiceInterface::class,\App\Modules\Definedform\Services\FormPresetFieldService::class);
        $this->app->singleton(\App\Modules\Definedform\Repositories\FormPresetFieldRepositoryInterface::class,\App\Modules\Definedform\Repositories\FormPresetFieldRepository::class);

        $this->app->singleton(\Ycsk\Definedform\Modules\Definedform\Services\FormFormatServiceInterface::class,\Ycsk\Definedform\Modules\Definedform\Services\FormFormatService::class);
        $this->app->singleton(\Ycsk\Definedform\Modules\Definedform\Repositories\FormFormatRepositoryInterface::class,\Ycsk\Definedform\Modules\Definedform\Repositories\FormFormatRepository::class);
        $this->app->singleton(\Ycsk\Definedform\Modules\Definedform\Services\FormFieldServiceInterface::class,\Ycsk\Definedform\Modules\Definedform\Services\FormFieldService::class);
        $this->app->singleton(\Ycsk\Definedform\Modules\Definedform\Repositories\FormFieldRepositoryInterface::class,\Ycsk\Definedform\Modules\Definedform\Repositories\FormFieldRepository::class);
        $this->app->singleton(\Ycsk\Definedform\Modules\Definedform\Services\FormListServiceInterface::class,\Ycsk\Definedform\Modules\Definedform\Services\FormListService::class);
        $this->app->singleton(\Ycsk\Definedform\Modules\Definedform\Repositories\FormListRepositoryInterface::class,\Ycsk\Definedform\Modules\Definedform\Repositories\FormListRepository::class);
        $this->app->singleton(\Ycsk\Definedform\Modules\Definedform\Services\FormLogServiceInterface::class,\Ycsk\Definedform\Modules\Definedform\Services\FormLogService::class);
        $this->app->singleton(\Ycsk\Definedform\Modules\Definedform\Repositories\FormLogRepositoryInterface::class,\Ycsk\Definedform\Modules\Definedform\Repositories\FormLogRepository::class);
        $this->app->singleton(\Ycsk\Definedform\Modules\Definedform\Services\FormMenuServiceInterface::class,\Ycsk\Definedform\Modules\Definedform\Services\FormMenuService::class);
        $this->app->singleton(\Ycsk\Definedform\Modules\Definedform\Repositories\FormMenuRepositoryInterface::class,\Ycsk\Definedform\Modules\Definedform\Repositories\FormMenuRepository::class);
        $this->app->singleton(\Ycsk\Definedform\Modules\Definedform\Services\FormListHeadServiceInterface::class,\Ycsk\Definedform\Modules\Definedform\Services\FormListHeadService::class);
        $this->app->singleton(\Ycsk\Definedform\Modules\Definedform\Repositories\FormListHeadRepositoryInterface::class,\Ycsk\Definedform\Modules\Definedform\Repositories\FormListHeadRepository::class);
        $this->app->singleton(\Ycsk\Definedform\Modules\Definedform\Services\OrderServiceInterface::class,\Ycsk\Definedform\Modules\Definedform\Services\OrderService::class);
        $this->app->singleton(\Ycsk\Definedform\Modules\Definedform\Repositories\OrderRepositoryInterface::class,\Ycsk\Definedform\Modules\Definedform\Repositories\OrderRepository::class);
        $this->app->singleton(\Ycsk\Definedform\Modules\Definedform\Services\WorkflowServiceInterface::class,\Ycsk\Definedform\Modules\Definedform\Services\WorkflowService::class);
        $this->app->singleton(\Ycsk\Definedform\Modules\Definedform\Repositories\WorkflowRepositoryInterface::class,\Ycsk\Definedform\Modules\Definedform\Repositories\WorkflowRepository::class);
        $this->app->singleton(\Ycsk\Definedform\Modules\Definedform\Services\FormPresetFieldServiceInterface::class,\Ycsk\Definedform\Modules\Definedform\Services\FormPresetFieldService::class);
        $this->app->singleton(\Ycsk\Definedform\Modules\Definedform\Repositories\FormFieldRepositoryInterface::class,\Ycsk\Definedform\Modules\Definedform\Repositories\FormFieldRepository::class);
    }
}