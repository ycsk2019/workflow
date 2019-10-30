<?php


namespace App\Modules\Definedform\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct(Request $request)
    {
        if (env('APP_DEBUG')) {
            \Log::debug("\n\n");
            \Log::debug('请求路由：' . $request->url());
            $requestParameters = $request->all();
            if (!empty($requestParameters)) {
                \Log::debug('请求参数：' . var_export($requestParameters, true));
            } else {
                \Log::debug('请求参数：无');
            }
        }
    }
}
