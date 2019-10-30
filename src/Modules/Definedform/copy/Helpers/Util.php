<?php


namespace App\Modules\Definedform\Helpers;


//use App\Services\Notify\NofityClient;
//use App\Services\NotifyService;
use Illuminate\Support\Facades\DB;

class Util
{
    /**
     * curlpost请求
     * @param $url
     * @param array $data
     * @return bool|mixed
     */
    public static function curlPost($url, $data = [])
    {
        //对空格进行转义
        $url = str_replace(' ', '+', $url);
        $ch  = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, "$url");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);  //定义超时3秒钟
        // POST数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // 把post的变量加上
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));    //所需传的数组用http_bulid_query()函数处理一下，就ok了
        //执行并获取url地址的内容
        $output    = curl_exec($ch);
        $errorCode = curl_errno($ch);
        //释放curl句柄
        curl_close($ch);
        if (0 !== $errorCode) {
            return false;
        }
        return $output;
    }

    /**
     * curl get请求
     * @param string $url
     * @param array $data
     * @return mixed
     */
    public static function curlGet($url = '', $data = [])
    {
        $post_url = $url . '?' . http_build_query($data);
        $curl     = curl_init();
        curl_setopt($curl, CURLOPT_URL, $post_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    /**
     * 发送日志报警
     * @param $data
     * @return bool|mixed
     */
    public static function monitor($data)
    {
        $url = env('SERVER_MONITOR_URL', '');
        if (empty($url)) {
            return;
        }
        return self::curlPost($url, $data);
    }

    /**
     * 生成业务id
     * @param int $type 备注见表id_*
     * @return int|string
     */
    public static function generateId($type)
    {
        $typeList = [21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60];
        //定义的类型范围内
        if (empty($type) || !in_array($type, $typeList)) {
            return 0;
        }
        DB::insert("REPLACE INTO `id_{$type}`(`stub`) VALUES(1);");
        $id       = DB::getPdo()->lastInsertId();
        $id       %= 999999; //求余，保留6位数，每日数据超过6位数，需要扩展位数
        $idFormat = sprintf("%06d", $id);
        $newId    = $type . date('ymd') . $idFormat;
        return $newId;
    }

    /**
     * 订单流程通知
     * @param $data ['customer_id'=>required, 'type'=>SMSClientInterface::TRADE_AUDIT_PASS]
     *
     * Util::tradeFlowNotify([
     * 'customer_id'=>1,
     * 'type'=>SMSClientInterface::TRADE_AUDIT_PASS,
     * ]);
     */
    public static function tradeFlowNotify($data)
    {
        //NofityClient::sendMessage($data);
    }


    /**
     * 订单流程客户经理推送
     * @param $adminId int 客户经理id
     * @param $type int    推送类型 NotifyService::AUDITING_PASS|AUDITING_NOT_PASS|PAY_DEPOSIT|PAY_FIRST|WATING_CAR
     * @param $params ['customer_name'=>'required','order_id'=>'']
     */
    public static function managerPush($adminId, $type, $params)
    {
        //NotifyService::managerPush($adminId, $type, $params);
    }

    /**
     * 判断一个四则运算表达式是否正确，错误返回false，正确返回计算结果
     * @param $str
     * @return bool|mixed
     */
    public static function checkFormula($str)
    {
        try {
            // 这个是为了防止注入
            if (!preg_match('/^((\d++(\.\d+)?|\((?1)\))((\+|\/|\*|-)(\d++(\.\d+)?|(?1)))*)$/', $str)) {
                return false;
            }
            // 这部计算是为了判断是否有0
            $res = eval("return {$str};");
            return $res;
        } catch (\Exception $e) {
            return false;
        }
    }
    /**
     * 生成24位唯一订单号码
     * 因太长显示不下，修改为18位
     *
     * @param $e StdClass对象实例
     * @return array|void
     */
    public static function random_order_id()
    {
        //生成24位唯一订单号码，格式：YYYY-MMDD-HHII-SS-NNNN,NNNN-CC，
        //其中：YYYY=年份，MM=月份，DD=日期，HH=24格式小时，II=分，SS=秒，NNNNNNNN=随机数，CC=检查码
        @date_default_timezone_set("PRC");
        //订购日期
        $order_date = date('Y-m-d');
        //订单号码主体（YYYYMMDDHHIISSNNNNNNNN）
        $order_id_main = date('YmdHis') . rand(10,99);
        //订单号码主体长度
        $order_id_len = strlen($order_id_main);
        $order_id_sum = 0;
        for ($i = 0; $i < $order_id_len; $i++) {
            $order_id_sum += (int)(substr($order_id_main, $i, 1));
        }
        //唯一订单号码（YYYYMMDDHHIISSNNNNNNNNCC）
        $order_id = $order_id_main . str_pad((100 - $order_id_sum % 100) % 100, 2, '0', STR_PAD_LEFT);

        return $order_id;
    }
}