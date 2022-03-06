<?php
namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\Event;
use yii\db\BaseActiveRecord;

class Config extends Component
{
    private static function configInit ()
    {
        if (isset(Yii::$app->session['language']))
        {
            Yii::$app->language = Yii::$app->session['language'];
        }
    }

    public static function backendInit()
    {
        Event::on(BaseActiveRecord::className(), BaseActiveRecord::EVENT_AFTER_INSERT, [
            AdminLog::className(), 'create'
        ]);
        Event::on(BaseActiveRecord::className(), BaseActiveRecord::EVENT_AFTER_UPDATE, [
            AdminLog::className(), 'update'
        ]);
        Event::on(BaseActiveRecord::className(), BaseActiveRecord::EVENT_AFTER_DELETE, [
            AdminLog::className(), 'delete'
        ]);

        self::configInit();
    }

    public static function frontendInit()
    {

    }
}