<?php


namespace App\Service;

use App\Models\Admin\SmsConfig;
use App\Models\Admin\System;
use Illuminate\Support\Facades\Log;
use Mrgoon\AliSms\AliSms;


/**
 * 阿里云短信类
 */
class AliyunSms
{
    /**
     * 发送短信
     */
    public static function sendSms($mobile, $params = [])
    {
        try {
            $ali_sms = new AliSms();
            $smsId = System::get()->first()['smsConfId'];
            if(empty($smsId)){
                $config = SmsConfig::get()->first();
            }else{
                $config = SmsConfig::find($smsId);
            }

            $code = rand(100000,999999);
            $response = $ali_sms->sendSms($mobile, $config['template_code'], ['code' => $code],$config->toArray());
            if ($response->Code == 'OK') {
                cache()->store('file')->put($mobile,$code,1);
                return true;
            }
            Log::error($response->Message);
            return false;
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return false;
        }
    }
}


