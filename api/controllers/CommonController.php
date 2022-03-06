<?php

namespace api\controllers;

class CommonController extends \yii\rest\ActiveController
{
    use \api\traits\Response;
    use \api\traits\Search;
    use \api\traits\Uploads;

    /** @var string 必须定义此属性 默认为空*/
    public $modelClass = '';

    /** @var bool 关闭csrf验证 */
    public $enableCsrfValidation = false;

    /**
     * @Notes: 错误
     * @Function actions
     * @return \string[][]
     * @author: Admin
     * @Time: 2022/2/21 15:55
     */
    public function actions()
    {
        return [
            'error'   => [
                'class' => 'api\actions\ErrorAction',
            ]
        ];
    }

    /**
     * @Notes: 获取实例
     * @Function getPrimaryKey
     * @return mixed
     * @author: Admin
     * @Time: 2022/2/23 14:19
     */
    protected function getObject ()
    {
        return \Yii::createObject(['class' => $this->modelClass]);
    }
}
