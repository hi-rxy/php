<?php
// +----------------------------------------------------------------------
// | 全局响应设置
// +----------------------------------------------------------------------
return [
    'class' => 'yii\web\Response',
    'on beforeSend' => function ( $event ) {
        $response = $event->sender;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $statusCode = $response->statusCode;
        switch ($statusCode) {
            case 200 :
                $returnData = $response->data;
                break;
            case 401 :
                $returnData = [
                    'code' => 401,
                    'message' => '没有权限',
                ];
                break;
            case 404 :
                $returnData = [
                    'code' => 404,
                    'message' => '接口不存在',
                ];
                break;
            default:
                $returnData = [
                    'code' => $statusCode,
                    'message' => isset($response->data['message']) ? $response->data['message'] : '',
                ];
                break;
        }
        $response->data = $returnData;
        $response->statusCode = 200;
    },
];
