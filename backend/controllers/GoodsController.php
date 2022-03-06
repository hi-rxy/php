<?php

namespace backend\controllers;

use backend\models\Goods;
use backend\models\GoodsStandard;
use backend\models\search\GoodsSearch;
//use jinxing\admin\behaviors\Logging;
use yii;
use yii\base\BaseObject;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * Class GoodsController 商品管理 执行操作控制器
 * @package backend\controllers
 */
class GoodsController extends Controller
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
     * @var $modelClass Goods
     */
    public $modelClass = 'common\models\Goods';

    /**
     * @var GoodsSearch string 定义使用的查询model
     */
    public $modelSearchClass = null;

    /**
     * @var string 定义上传文件使用的model
     */
    public $uploadFromClass = 'backend\models\forms\GoodsUploadForm';

    /**
     * @var string 定义上传文件的目录
     */
    public $strUploadPath = './uploads/goods/';
    
    /**
     * 需要定义where 方法，确定前端查询字段对应的查询方式
     * 
     * @return array 
     */
    public function where()
    {
        return [];
    }

//    /**
//     * 行为
//     *
//     * @return array
//     */
//    public function behaviors()
//    {
//        return ArrayHelper::merge(
//            parent::behaviors(),
//            [
//                'logging' => [
//                    'class' => Logging::className(),
//                    'needLogActions' => [
//                        'create',
//                        'update',
//                        'delete',
//                        'delete-all',
//                        'editable',
//                        'upload',
//                        'audit'
//                    ]
//                ]
//            ]
//        );
//    }

    public function actions()
    {
        return [
            'upload' => [
                'class' => 'cliff363825\kindeditor\KindEditorUploadAction',
                'basePath' => '@frontend/web/uploads/kindEditor/goods', //图片保存的物理路径
                'baseUrl' => Yii::$app->params['site']['UPLOAD_PATH'].'kindEditor/goods',//图片的url
                'maxSize' => 2097152, //图片的限制
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (is_null($this->modelSearchClass)) $this->modelSearchClass = new GoodsSearch();
        return parent::beforeAction($action);
    }

    /**
     * 查询条件
     * @param array|mixed $where
     * @return yii\db\ActiveQuery|yii\db\Query
     */
    protected function getQuery($where)
    {
        $get     = Yii::$app->request->get('filters', []);
        $post    = Yii::$app->request->post('filters', []);
        $filters = array_merge($get,$post);
        return $this->modelSearchClass->search($filters)->joinWith(['store','storeClass','goodsClass'])->asArray();
    }

    /**
     * 查询后的数据修改
     * @param mixed $array
     */
    protected function afterSearch(&$array)
    {
        return $this->modelSearchClass->afterSearch($array);
    }

    /**
     * 商品管理页面
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index',[
            'arrGoodsStatus' => Goods::getArrayStatus(),
            'arrGoodsColor'  => Goods::getStatusColor()
        ]);
    }

    /**
     * 商品发布
     * @return mixed|string
     */
    public function actionCreate()
    {
        if (Yii::$app->request->isPost)
        {
            $postData = Yii::$app->request->post();
            $data     = Goods::formatData($postData);
            try {
                $result = Goods::doCreate((new $this->modelClass), $data);
                if (is_object($result) && $result instanceof Goods) {
                    return $this->success($result,'保存成功');
                }
            } catch (yii\base\Exception $exception) {
                return $this->error(1001, $exception->getMessage());
            }
        }

        $model = new Goods();
        return $this->render("_form",[
            'model'         => $model,
            'goods'         => Goods::getGoods($model)
        ]);
    }

    /**
     * 商品修改
     * @return mixed|string
     */
    public function actionUpdate()
    {
        /** @var Goods $model */
        $model = Goods::findOne(Yii::$app->request->get('id'));

        if (Yii::$app->request->isPost)
        {
            $postData = Yii::$app->request->post();
            $data     = Goods::formatData($postData);
            try {
                $result = Goods::doUpdate($model,$data);
                if (is_object($result) && $result instanceof Goods) {
                    return $this->success($result,'保存成功');
                }
            } catch (yii\base\Exception $exception) {
                return $this->error(1001, $exception->getMessage());
            }
        }

        return $this->render("_form",[
            'model' => $model,
            'goods' => Goods::getGoods($model),
        ]);
    }

    /**
     * 商品详情
     */
    public function actionView ()
    {
        $this->layout = "@backend/template/layouts/main";
        $this->viewPath = "@backend/template/";

        /** @var Goods $model */
        $model = Goods::findOne(Yii::$app->request->get('id'));
        return $this->render('goods/index',[
            'model' => $model,
            'details' => $this->modelSearchClass->getGoodsDetails($model)
        ]);
    }

    /**
     * 重写图片上传
     * @return mixed|string
     */
    public function actionUpload ()
    {
        $field = Yii::$app->request->get('sField', ''); // 上传字段
        $model = new $this->uploadFromClass();

        try {
            if (empty($field)) return $this->error();

            // 判断删除之前的文件
            $strFile     = (string)Yii::$app->request->post($field);   // 旧的地址
            $oldFilePath = (string)Yii::getAlias('@uploadPath') . '/' . $strFile;
            if (!empty($strFile) && file_exists($oldFilePath)) unlink($oldFilePath);

            // 判断是否存在指定的验证场景，有则使用，没有默认
            /* @var $model Model */
            if (ArrayHelper::getValue($model->scenarios(), $field)) $model->scenario = $field;

            // 上传文件
            $uploadFile = $model->$field = UploadedFile::getInstance($model, $field);
            if (empty($uploadFile)) return $this->error(203,'未上传任何文件');
            $result = $model->upload($uploadFile,$field);

            return $this->success([
                'absolute_path' => $result,
                'relative_path' => str_replace((Yii::$app->request->hostInfo.'/uploads'), '', $result)
            ]);
        } catch (yii\base\Exception $exception) {
            return $this->error(203,$exception->getMessage());
        }
    }

    /**
     * 审核商品
     * @return mixed|string
     */
    public function actionAudit ()
    {
        if (Yii::$app->request->isPost)
        {
            $post       = Yii::$app->request->post();
            $goods_id   = (int)Yii::$app->request->get('id','');
            if ($goods_id == '') $goods_id = Yii::$app->request->post('ids',[]);
            Goods::doSaveField($goods_id,$post);
            return $this->success();
        }

        return $this->render('form/_audit');
    }

    /**
     * 获取商品规格
     * @return mixed|string
     */
    public function actionGetStandard ()
    {
        if (Yii::$app->request->isPost)
        {
            $goods_id = (int)Yii::$app->request->post('goods_id',0);
            $data = GoodsStandard::find()->where(['goods_id' => $goods_id])->asArray()->all();
            return $this->success($data);
        }
        return $this->error();
    }

    /**
     * 检查商品库存
     * @return mixed|string
     */
    public function actionCheckStock ()
    {
        if (Yii::$app->request->isPost) 
        {
            $spec_id = Yii::$app->request->post('spec_id',[]);
            $spec_id = array_map('intval', $spec_id);
            $spec_id = implode(",", $spec_id);
            $goods_num = Yii::$app->request->post('goods_num',0);
            $goods_id  = Yii::$app->request->post('goods_id',0);

            $goods = $this->modelSearchClass->search(['goods_id' => $goods_id, 'status' => Goods::STATUS_AUDITED])->asArray()->one();
            if (empty($goods)) return $this->error(201,'该商品已经下架');

            if ($spec_id) {
                $standards = GoodsStandard::findOne(['name' => $spec_id, 'goods_id' => $goods_id, 'status' => 1]);
                if (is_null($standards)) return $this->error(201,'该商品的规格组合不存在');

                if ($standards->stock < $goods_num) return $this->error(201,'该商品的规格库存量不足');
            } else {
                if ($goods['stock'] < $goods_num) return $this->error(201,'该商品的库存量不足');
            }
            return $this->success();
        }
        return $this->error();
    }
}