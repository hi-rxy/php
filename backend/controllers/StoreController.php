<?php

namespace backend\controllers;

use yii;
use common\models\Store;
use jinxing\admin\helpers\Helper;
use jinxing\admin\models\China;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * Class StoreController 店铺管理 执行操作控制器
 * @package backend\controllers
 */
class StoreController extends Controller
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
    public $modelClass = 'common\models\Store';

    /**
     * @var string 定义上传文件的目录
     */
    public $strUploadPath = './uploads/store/';

    /**
     * @var string 二级目录-店铺
     */
    private $uploadDir = 'store';

    /**
     * @var string 上传使用uploadForm 类名
     */
    public $uploadFromClass = 'backend\models\forms\UploadForm';

    /**
     * @var array  目录路径映射
     */
    private $uploadPathMap = [];

    /**
     * 初始化
     */
    public function init()
    {
        parent::init();
        $this->strUploadPath = Yii::getAlias("@uploadPath");
        $this->uploadPathMap = [
            'logo'          => $this->uploadDir.'/logo', // 店铺LOGO
            'id_card_front' => $this->uploadDir.'/idCardFront', // 店铺-拥有者身份证正面
            'id_card_side'  => $this->uploadDir.'/idCardSide', // 店铺-拥有者身份证反面
        ];
    }

    /**
     * 需要定义where 方法，确定前端查询字段对应的查询方式
     *
     * @return array
     */
    public function where()
    {
        return [
            [['status','is_open_store'], '='],
            [['name'], 'like'],
        ];
    }

    /**
     * 店铺页面
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'arrStoreStatus' => Store::getStoreArrayStatus(),
            'arrStoreStatusColor' => Store::getStoreStatusColor(),
            'arrStoreDomainStatus' => Store::getStoreDomainArrayStatus(),
            'arrStoreDomainStatusColor' => Store::getStoreDomainStatusColor(),
            'arrChinaAddress' => Helper::map(China::find()->select(['id', 'name'])->where(['pid' => 0])->asArray()->all(), 'id', 'name'),
            'arrChinaCity' => ['请选择市'],
            'arrChinaArea' => ['请选择区'],
        ]);
    }

    /**
     * 重写上传方法
     * @return mixed|string
     */
    public function actionUpload()
    {
        // 接收参数
        $request  = Yii::$app->request;
        $strField = $request->get('sField');    // 上传文件表单名称
        if (empty($strField)) {
            return $this->error(201);
        }

        // 判断删除之前的文件
        $strFile = (string)$request->post($strField);   // 旧的地址
        if (!empty($strFile)) {
            $filePath = $this->strUploadPath . '/' . $strFile;
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }

        // 初始化上次表单model对象，并定义好验证场景
        $className = $this->uploadFromClass;
        /* @var $model Model */
        $model = new $className();
        // 判断是否存在指定的验证场景，有则使用，没有默认
        if (ArrayHelper::getValue($model->scenarios(), $strField)) {
            $model->scenario = $strField;
        }

        try {
            // 上传文件
            $objFile = $model->$strField = UploadedFile::getInstance($model, $strField);
            if (empty($objFile)) {
                throw new \UnexpectedValueException(Yii::t('admin', 'No file upload'));
            }

            // 验证
            if (!$model->validate()) {
                throw new \UnexpectedValueException($model->getFirstError($strField));
            }

            // 定义好保存文件目录，目录不存在那么创建
            $uploadAbsolutePath = $this->strUploadPath . '/' .$this->uploadPathMap[$strField] . '/' . date("Y-m") . '/' . date("d");
            FileHelper::createDirectory($uploadAbsolutePath);
            if (!file_exists($uploadAbsolutePath)) {
                throw new \UnexpectedValueException(Yii::t('admin', 'Directory creation failed') . $uploadAbsolutePath);
            }

            // 生成文件随机名
            $strFilePath = $uploadAbsolutePath . '/' . md5(time()) . '.' . $objFile->extension;
            // 执行文件上传保存，
            if (!$objFile->saveAs($strFilePath)) {
                return $this->error(204);
            }

            // 如果自定义了上传之后的处理, 那么执行自定义的方法
            if (method_exists($this, 'afterUpload')) {
                $strFilePath = $this->afterUpload($strFilePath, $strField, $objFile);
                if (!$strFilePath) {
                    return $this->error(204);
                }
            }

            return $this->success([
                'sFilePath' => str_replace($this->strUploadPath, '', $strFilePath),
                'sFileName' => $objFile->baseName . '.' . $objFile->extension,
            ]);
        } catch (\Exception $e) {
            return $this->error(203, $e->getMessage());
        }
    }

    /**
     * 获取城市
     * @return mixed|string
     */
    public function actionGetChinaCity()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $html = '<option value="0">请选择</option>';

            if (!empty($post['cityId'])) {
                $data = China::find()->select(['id', 'name'])->where(['pid' => $post['cityId']])->asArray()->all();

                foreach ($data as $city) {
                    $selected = '';
                    if (isset($post['selectedId']) && $post['selectedId'] && $post['selectedId'] == $city['id']){
                        $selected = "selected";
                    }
                    $html .= '<option '.$selected.' value="' . $city['id'] . '">' . $city['name'] . '</option>';
                }
            }

            return $this->success(['options' => $html]);
        }
        return $this->returnJson();
    }


    /*public function afterSearch(&$array)
    {
        foreach ($array as &$item) {
            $item['logo']           = Yii::$app->request->hostInfo .'/uploads'. $item['logo'];
            $item['id_card_front']  = Yii::$app->request->hostInfo .'/uploads'. $item['id_card_front'];
            $item['id_card_side']   = Yii::$app->request->hostInfo .'/uploads'. $item['id_card_side'];
        }
        unset($item);
    }*/
}
