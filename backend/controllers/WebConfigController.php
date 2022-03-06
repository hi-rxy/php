<?php

namespace backend\controllers;

//use jinxing\admin\behaviors\Logging;
use Yii;
use common\models\Config;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

/**
 * Class ConfigController 配置参数管理 执行操作控制器
 * @package backend\controllers
 */
class WebConfigController extends Controller
{
    /**
     * @var string pk 定义表使用的主键名称
     */
    protected $pk = 'id';

    /**
     * @var string sort 定义默认排序字段名称
     */
    protected $sort = 'id';

    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'common\models\Config';

    /**
     * 在行为中开启 http缓存
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
//                'logging' => [
//                    'class' => Logging::className(),
//                    'needLogActions' => [
//                        'create',
//                        'update',
//                        'delete',
//                        'delete-all',
//                        'editable',
//                        'upload'
//                    ]
//                ],
                'HttpCache' => [
                    'class' => 'yii\filters\HttpCache',
                    'only' => ['index'],
                    'lastModified' => function ($action, $params) {
                        $q = new \yii\db\Query();
                        return $q->from(Config::tableName())->max('updated_at');
                    },
                ]
            ]
        );
    }

    /**
     * 需要定义where 方法，确定前端查询字段对应的查询方式
     *
     * @return array
     */
    public function where()
    {
        return [];
    }

    /**
     * 网站配置页面
     * @return string
     */
    public function actionIndex()
    {
        // 打开页面查找redis中是否存在配置
        if (!Yii::$app->cache->exists($this->redisKey[0])) {
            $data = Config::getConfigs(['status' => Config::STATUS_OPEN]);
        } else {
            $data = Yii::$app->cache->get($this->redisKey[0]);
        }
        return $this->render('index', [
            'data' => $data
        ]);
    }

    /**
     * 网站配置修改
     * @throws Exception
     */
    public function actionUpdate()
    {
        if (Yii::$app->request->isPost)
        {
            $data = Yii::$app->request->post();

            // 将配置写入文件中
            Config::_update($data['config']);

            // 同时将配置写入到缓存中
            Yii::$app->cache->set($this->redisKey[0], Config::getConfigs(['status' => Config::STATUS_OPEN]), 86400);

            return $this->success([], '修改成功');
        }
        return $this->error();
    }
}
