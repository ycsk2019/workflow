# 自定义表单V1
## 安装
- 1.composer require Ycsk2019/definedform:dev-master -vvv
- 2.php artisan vendor:publish --tag=definedform --force
- 3.php artisan Ycsk2019:definedform-install
- 4.php artisan Ycsk2019:definedformdata-install
- 5.\app\Providers\RouteServiceProvider.php  

  添加代码：
  public function map() 中添加：
  {
  
      $this->mapFormRoutes();
  
  }  
  新增：
  protected function mapFormRoutes()
  {
      Route::namespace('')
          ->group(base_path('routes/definedform.php'));
  }
## 使用
- 使用预置路由与控制器方法
http://xxx/definedformsrc/**   
具体路由请查看\根目录\routes\defineform.php

- 使用自定义路由与控制器方法
http://xxx/definedform/**  
具体路由请查看\根目录\vendor\Ycsk2019\definedform\src\routes.php

- 除了路由，自定义控制器，服务类，模型类等均在\根目录\Modules\，可自行修改使用
