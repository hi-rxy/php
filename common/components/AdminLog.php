<?php
namespace common\components;

use Yii;
use jinxing\admin\models\AdminLog AS AdminLogModel;

class AdminLog extends \yii\base\Event
{
    /**
     * 数据库新增保存日志
     *
     * @param $event
     * @throws \Throwable
     */
    public static function create($event)
    {
        if ($event->sender->className() !== \jinxing\admin\models\AdminLog::className()) {
            $desc = '<br>';
            foreach ($event->sender->getAttributes() as $name => $value) {
                !is_string( $value ) && $value = print_r($value, true);
                $desc .= $event->sender->getAttributeLabel($name) . '(' . $name . ') => ' . $value . ',<br>';
            }
            $desc = substr($desc, 0, -5);
            $model = new AdminLogModel;
            $class = $event->sender->className();
            $idDes = '';
            if (isset($event->sender->id)) {
                $idDes = '{{%ID%}} ' . $event->sender->id;
            }
            $model->index = (string)$event->sender->getAttributes()['id'];
            $model->response = '{{%ADMIN_USER%}} [ ' . Yii::$app->getUser()->getIdentity()->username . ' ] {{%BY%}} ' . $class . ' [ ' . $class::tableName() . ' ] ' . " {{%CREATED%}} {$idDes} {{%RECORD%}}: " . $desc;
            $model->action = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
            $model->save();
        }
    }

    /**
     * 数据库修改保存日志
     *
     * @param $event
     * @throws \Throwable
     */
    public static function update($event)
    {
        if (! empty($event->changedAttributes)) {
            $desc = '<br>';
            $oldAttributes = $event->sender->oldAttributes;
            foreach ($event->changedAttributes as $name => $value) {
                if( $oldAttributes[$name] == $value ) continue;
                !is_string( $value ) && $value = print_r($value, true);
                $desc .= $event->sender->getAttributeLabel($name) . '(' . $name . ') : ' . $value . '=>' . $event->sender->oldAttributes[$name] . ',<br>';
            }
            $desc = substr($desc, 0, -5);
            $model = new AdminLogModel;
            $class = $event->sender->className();
            $idDes = '';
            if (isset($event->sender->id)) {
                $idDes = '{{%ID%}} ' . $event->sender->id;
            }
            $model->index = (string)$event->sender->getAttributes()['id'];
            $model->response = '{{%ADMIN_USER%}} [ ' . Yii::$app->getUser()->getIdentity()->username . ' ] {{%BY%}} ' . $class . ' [ ' . $class::tableName() . ' ] ' . " {{%UPDATED%}} {$idDes} {{%RECORD%}}: " . $desc;
            $model->action = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
            $model->save();
        }
    }

    /**
     * 数据库删除保存日志
     *
     * @param $event
     * @throws \Throwable
     */
    public static function delete($event)
    {
        $desc = '<br>';
        foreach ($event->sender->getAttributes() as $name => $value) {
            !is_string( $value ) && $value = print_r($value, true);
            $desc .= $event->sender->getAttributeLabel($name) . '(' . $name . ') => ' . $value . ',<br>';
        }
        $desc = substr($desc, 0, -5);
        $model = new AdminLogModel;
        $class = $event->sender->className();
        $idDes = '';
        if (isset($event->sender->id)) {
            $idDes = '{{%ID%}} ' . $event->sender->id;
        }
        $model->index = (string)$event->sender->getAttributes()['id'];
        $model->response = '{{%ADMIN_USER%}} [ ' . Yii::$app->getUser()->getIdentity()->username . ' ] {{%BY%}} ' . $class . ' [ ' . $class::tableName() . ' ] ' . " {{%DELETED%}} {$idDes} {{%RECORD%}}: " . $desc;
        $model->action = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
        $model->save();
    }
}