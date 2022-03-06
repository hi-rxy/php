<?php
namespace api\modules\web\controllers;

use api\controllers\AuthController;
use api\models\web\ConfigGroup;

class ConfigGroupController extends AuthController
{
    public $modelClass = "api\models\web\ConfigGroup";

    /**
     * @Notes: 详情
     * @Function actionView
     * @param $name
     * @return array
     * @author: Admin
     * @Time: 2022/3/3 16:13
     */
    public function actionView ($name)
    {
        $group = ConfigGroup::queryCondition()->andWhere(['name' => $name])->one();
        $group['status'] = ConfigGroup::getStatusColor($group['status']);
        return $this->success($group);
    }

    /**
     * @Notes: 添加
     * @Function actionCreate
     * @return array
     * @throws \api\exceptions\ErrorMsgException
     * @throws \api\exceptions\HttpBadRequestException
     * @author: Admin
     * @Time: 2022/3/3 8:50
     */
    public function actionCreate ()
    {
        $data = $this->request->post();
        return $this->success(ConfigGroup::store($this->getObject(), $this->handleParams($data)));
    }

    /**
     * @Notes: 修改
     * @Function actionUpdate
     * @return array
     * @throws \api\exceptions\ErrorMsgException
     * @throws \api\exceptions\HttpBadRequestException
     * @author: Admin
     * @Time: 2022/3/3 8:50
     */
    public function actionUpdate ()
    {
        $data = $this->request->post();
        return $this->success(ConfigGroup::store($this->getObject(), $this->handleParams($data)));
    }

    /**
     * @Notes: 删除
     * @Function actionDelete
     * @return array
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @author: Admin
     * @Time: 2022/3/3 8:50
     */
    public function actionDelete ()
    {
        return $this->success(ConfigGroup::del($this->getObject(), $this->request->post()));
    }

    /**
     * @Notes: 查询修改
     * @Function afterSearch
     * @param array $array
     * @author: Admin
     * @Time: 2022/2/23 14:20
     */
    protected function afterSearch(&$array = [])
    {
        foreach ($array as $vo => &$item) {
            $item['status'] = ConfigGroup::getStatusColor($item['status']);
            $item['created_at'] = date('Y-m-d H:i:s',$item['created_at']);
            $item['updated_at'] = date('Y-m-d H:i:s',$item['updated_at']);
        }
    }

    /**
     * @Notes: 其他参数处理
     * @Function handleParams
     * @param $data
     * @return mixed
     * @author: Admin
     * @Time: 2022/2/26 11:55
     */
    private function handleParams ($data)
    {
        $status = array_flip(ConfigGroup::getStatusColor());
        if (!in_array($data['status'],array_keys($status))) {
            return $data;
        }
        $data['status'] = $status[$data['status']];
        return $data;
    }
}
