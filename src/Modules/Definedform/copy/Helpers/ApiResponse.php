<?php

namespace App\Modules\Definedform\Helpers;

trait ApiResponse
{



    public function respondSuccessWithSimplePagination(
        $worksPaginator,
        $transformer = null
    )
    {
        if ($transformer) {
            $items = $transformer->transFormCollection(
                $worksPaginator->items()
            );
        } else {
            $items = $worksPaginator->items();
        }

        return $this->respondDefaultSuccess(
            $items
        );
    }


    /**
     * @param bool $data
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response|\Illuminate\Http\Response
     */
    public function respondDefaultSuccess($data = true)
    {
        if ($data === null) {
            $data = new \stdClass();
        }

        return self::end($data, 200, null, null, false);
    }


    /**
     * @param \Throwable $throwable
     * @param null $code 要覆盖 throwable 的 code
     * @param null $message 要覆盖 throwable 的 message
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response|\Illuminate\Http\Response
     */
    public static function respondDefaultFail(
        \Throwable $throwable,
        $code =
        null,
        $message = null
    )
    {
        return self::end(
            false,
            $code ?? $throwable->getCode(),
            $message ?? $throwable->getMessage(),
            null,
            false);
    }


    public function respondSuccessWithPagination(
        $worksPaginator,
        $transformer = null
    )
    {
        if ($transformer) {
            $items = $transformer->transFormCollection(
                $worksPaginator->items()
            );
        } else {
            $items = $worksPaginator->items();
        }

        return $this->respondDefaultSuccess(
            [
                "list"  => $items,
                "total" => $worksPaginator->total(),
            ]
        );
    }

    public function respondSuccessThroughTransformCollection(
        $items,
        $transformer = null
    )
    {
        $items = $transformer->transFormCollection(
            $items
        );

        return $this->respondDefaultSuccess(
            $items
        );
    }

    public function respondSuccessThroughTransformer($item, $transformer = null)
    {
        if ($transformer !== null) {
            $data = $transformer->transform($item);
        } else {
            $data = $item;
        }

        return $this->respondDefaultSuccess($data);
    }


    /**
     * out 方法的别名 兼容 福生 和文虎的风格
     * @param array $result
     * @param int $code
     * @param string $msg
     * @param array $debug
     * @param bool $directOutput
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public static function end($result = [], $code = 200, $msg = '', $debug = [], $directOutput = false)
    {
        return self::out($result, $code, $msg, $debug, $directOutput);
    }


    /**
     * 响应输出
     * @param  $result
     * @param int $code
     * @param string $msg
     * @param array $debug
     * @param bool $directOutput
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @return \Illuminate\Http\Response
     */
    public static function out($result = true, $code = 200, $msg = '', $debug = [], $directOutput = false)
    {

        $msg     = empty($msg) ? '' : $msg;

        $output = [
            'success'   => $code == 200 ? true : false,
            'error_no'  => (int)$code,
            'error_msg' => $msg,
            'result'    => $result,
        ];

        if (config('app.debug')) {
            $output['_debug'] = $debug;
        }
        if ($directOutput == false) {
            return response($output);
        }
        response($output)->send();
        die();
    }

    /**
     * 直接输出
     * @param bool $result
     * @param int $code
     * @param string $msg
     */
    public static function output($result = true, $code = 200, $msg = '')
    {
        self::out($result, $code, $msg, [], true);
    }

}