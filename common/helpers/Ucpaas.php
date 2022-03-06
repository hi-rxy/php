<?php

namespace common\helpers;

use yii;
use yii\base\Exception;

class Ucpaas
{
    //API请求地址
    const BaseUrl = "https://open.ucpaas.com/ol/sms/";

    //开发者账号ID。由32个英文字母和阿拉伯数字组成的开发者账号唯一标识符。
    private $accountSid;

    //开发者账号TOKEN
    private $token;

    //应用APP_ID
    private $appId;

    public function __construct($options)
    {
        if (is_array($options) && !empty($options)) {
            $this->accountSid   = isset($options['SMS_ACCOUNT_SID']) ? $options['SMS_ACCOUNT_SID'] : '';
            $this->token        = isset($options['SMS_TOKEN']) ? $options['SMS_TOKEN'] : '';
            $this->appId        = isset($options['SMS_APP_ID']) ? $options['SMS_APP_ID'] : '';
        }
    }

    /**
     * @param $url
     * @param null $body
     * @param string $method
     * @return bool|false|string
     * @throws Exception
     */
    private function getResult($url, $body = null, $method = 'POST')
    {
        $data = $this->_http($url, $body, $method);
        if (isset($data) && !empty($data)) {
            $result = $data;
        } else {
            $result = '没有返回数据';
        }
        return $result;
    }

    /**
     * @param $url
     * @param $params
     * @param string $method
     * @param bool $multi
     * @return bool|false|string
     * @throws Exception
     */
    private function _http($url, $params, $method = 'POST', $multi = false)
    {
        if (function_exists("curl_init")) {
            $header = [
                'Accept:application/json',
                'Content-Type:application/json;charset=utf-8',
            ];
            $opts = [
                CURLOPT_TIMEOUT             => 30,
                CURLOPT_RETURNTRANSFER      => 1,
                CURLOPT_SSL_VERIFYPEER      => false,
                CURLOPT_SSL_VERIFYHOST      => false,
                CURLOPT_HTTPHEADER          => $header
            ];
            // 根据请求类型设置特定参数
            switch (strtoupper($method)) {
                case 'GET':
                    $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
                    break;
                case 'POST':
                    //判断是否传输文件
                    $params = $multi ? $params : http_build_query($params);
                    $opts[CURLOPT_URL] = $url;
                    $opts[CURLOPT_POST] = 1;
                    $opts[CURLOPT_POSTFIELDS] = $params;
                    break;
                default:
                    throw new Exception('不支持的请求方式！');
            }
            // 初始化并执行curl请求
            $ch = curl_init();
            curl_setopt_array($ch, $opts);
            $data   = curl_exec($ch);
            $error  = curl_error($ch);
            curl_close($ch);
            if ($error) throw new Exception('请求发生错误：' . $error);
        } else {
            $opts = [];
            $headers = [
                "method" => strtoupper($method),
            ];
            $headers[]              = 'Accept:application/json';
            $headers['header'][]    = 'Content-Type:application/json;charset=utf-8';
            if (!empty($body)) {
                $headers['header'][]    = 'Content-Length:' . strlen($body);
                $headers['content']     = $body;
            }
            $opts['http'] = $headers;
            $data = file_get_contents($url, false, stream_context_create($opts));
        }
        return $data;
    }

    /**
     * 单条发送短信的function，适用于注册/找回密码/认证/操作提醒等单个用户单条短信的发送场景
     * @param $templateId
     * @param null $param 变量参数，多个参数使用英文逗号隔开（如：param=“a,b,c”）
     * @param string $mobile 接收短信的手机号码
     * @param int $uid 用于贵司标识短信的参数，按需选填。
     * @return mixed|string
     * @throws Exception
     */
    public function SendSms($templateId, $mobile, $param = '', $uid = '')
    {
        $url = self::BaseUrl . 'sendsms';
        $body_json = [
            'sid'       => $this->accountSid,
            'token'     => $this->token,
            'appid'     => $this->appId,
            'templateid'=> $templateId,
            'param'     => $param,
            'mobile'    => $mobile,
            'uid'       => $uid,
        ];
        $body = json_encode($body_json);
        return $this->getResult($url, $body);
    }

    /**
     *群发送短信的function，适用于运营/告警/批量通知等多用户的发送场景
     * @param $templateId
     * @param string $param 变量参数，多个参数使用英文逗号隔开（如：param=“a,b,c”）
     * @param string $mobileList 接收短信的手机号码，多个号码将用英文逗号隔开，如“18088888888,15055555555,13100000000”
     * @param int $uid 用于贵司标识短信的参数，按需选填。
     * @return mixed|string
     * @throws Exception
     */
    public function SendSmsByBatch($templateId, $mobileList, $param = '', $uid = '')
    {
        $url = self::BaseUrl . 'sendsms_batch';
        $body_json = array(
            'sid'       => $this->accountSid,
            'token'     => $this->token,
            'appid'     => $this->appId,
            'templateid'=> $templateId,
            'param'     => $param,
            'mobile'    => $mobileList,
            'uid'       => $uid,
        );
        $body = json_encode($body_json);
        return $this->getResult($url, $body);
    }
}
