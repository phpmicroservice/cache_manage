<?php

namespace App\Helper\Cache\juhe;

/**
 * Description of Exp
 * 快递
 * @author dongasai
 */
class ExpIndex
        extends \App\Helper\AbstractCache
{

    protected $ttl       = 3600;
    protected $param_arr = [
        'com'           => '',
        'no'            => '',
        'senderPhone'   => '',
        'receiverPhone' => ''
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
        $host = "https://expresslnt.market.alicloudapi.com";
        $path = "/kdi";
        $method = "GET";
        $appcode = env('ALIYUN_EXP_LNT');
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $querys = "no=".$this->param_arr['no'];
        if($this->param_arr['com']??0){
            $querys =  $querys .'&type='.$this->param_arr['com'];
        }

        $bodys = "";
        $url = $host . $path . "?" . $querys;

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
        $output =   curl_exec($curl);
        $info = curl_getinfo($curl);

        curl_close($curl);
        if($info['http_code']!=200){
            return [];
        }
        $json = json_decode($output,true);
        return $json['result'];
    }

}
