<?php

namespace api\traits;

trait Response
{
    # 成功状态码
    private $success_code = 200;

    # 失败状态码
    private $fail_code = 500;

    # 返回格式
    private $json = [
        'code'      => '' ,
        'message'   => '' ,
        'data'      => '' ,
    ];

    public function setCode ($code)
    {
        $this->json['code'] = $code;
    }

    public function setMessage ($msg)
    {
        $this->json['message'] = $msg;
    }

    public function handleJson ($arr = [])
    {
        return array_merge($this->json , $arr);
    }

    /**
     * 返回成功
     * @param $data
     * @param $msg
     * @return array
     */
    public function success( $data = [] ,  $msg = '操作成功' )
    {
        return $this->handleJson([
            'code'      => $this->success_code ,
            'message'   => $msg ,
            'data'      => $data ,
        ]);
    }

    /**
     * 返回失败
     * @param $msg
     * @param $data
     * @return array
     */
    public function error( $msg = '操作失败' , $data = [])
    {
        return $this->handleJson([
            'code'      => $this->fail_code ,
            'message'   => $msg ,
            'data'      => $data ,
        ]);
    }
}
