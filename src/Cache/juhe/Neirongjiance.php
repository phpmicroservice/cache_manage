<?php


namespace App\Helper\Cache\juhe;


use Illuminate\Support\Facades\Log;

class Neirongjiance   extends \App\Helper\AbstractCache
{

    protected $ttl = 3600;
    protected $param_arr = [
        'string' => ''
    ];

    /**
     * 清空
     */
    public function flush()
    {
        ;
    }

    /**
     * 涉及标签
     * @return array
     */
    public function tags(): array
    {
        return [
        ];
    }

    public function handle()
    {
        $string = $this->param_arr['string'];
        $host = "http://monitoring.market.alicloudapi.com";
        $path = "/neirongjiance";
        $method = "POST";
        $appcode = env('ALIYUN_NRIRONGJIANCE');
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        //根据API的要求，定义相对应的Content-Type
        array_push($headers, "Content-Type".":"."application/x-www-form-urlencoded; charset=UTF-8");
        $querys = "";
        $bodys = "in=".$string;
        $url = $host . $path;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $bodys);

        $output =   curl_exec($curl);
        $info = curl_getinfo($curl);

        curl_close($curl);
        if($info['http_code']!=200){
            Log::error("Neirongjiance:",[$info,$this->param_arr]);
            throw new \Exception('API请求出错!');
        }
        $json = json_decode($output,true);
        return $json;
    }
}
