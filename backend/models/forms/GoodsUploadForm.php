<?php

namespace backend\models\forms;

use jinxing\admin\helpers\Helper;
use yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * Class GoodsUploadForm 商品模型图片上传模型
 *
 * @package app\models\forms
 */
class GoodsUploadForm extends Model
{
    /** @var UploadedFile 规格图片 */
    public $attr_pic;

    /** @var UploadedFile 商品相册 */
    public $album;

    /** @var string 二级目录-商品规格图片 */
    private $uploadDir = 'goods';

    /** @var array  三级目录路径映射*/
    private $uploadPathMap = [];

    /**
     * 初始化
     */
    public function init()
    {
        parent::init();

        $this->uploadPathMap = [
            'attr_pic' => $this->uploadDir.'/standard/attr_pic', // 规格图片
            'album'    => $this->uploadDir.'/standard/album', // 商品相册
        ];
    }

    /**
     * 设置应用场景
     *
     * @return array
     */
    public function scenarios()
    {
        return [
            // 场景名称和字段名称一致
            'attr_pic' => ['attr_pic'],
            'album' => ['album'],
        ];
    }

    // 验证规则
    public function rules()
    {
        return [
            // 定义字段的验证规则，注意需要定义场景
            [
                ['attr_pic'],
                'file',
                'extensions' => ['png', 'jpg', 'gif', 'jpeg'],
                'on' => 'attr_pic'
            ],
            [
                ['album'],
                'file',
                'extensions' => ['png', 'jpg', 'gif', 'jpeg'],
                'on' => 'album'
            ],
        ];
    }

    /**
     * 上传图片
     * @param $objFile
     * @param $field
     * @return string
     * @throws yii\base\Exception
     */
    public function upload($objFile,$field)
    {
        if ($this->validate()) {
            $uploadAbsolutePath = Yii::getAlias('@uploadPath').'/'.$this->uploadPathMap[$field] . '/' . date("Y-m") . '/' . date("d");
            if (!is_dir($uploadAbsolutePath) || !is_writable($uploadAbsolutePath)) {
                FileHelper::createDirectory($uploadAbsolutePath, 0777, true);
            }

            $filePath = $uploadAbsolutePath . '/' . md5(time()) . '.' . $objFile->extension;
            if ($objFile->saveAs($filePath)) {
                return $this->parseImageUrl($filePath,$field);
            } else {
                throw new \Exception(Helper::arrayToString($objFile->getHasError()));
            }
        } else {
            throw new \Exception(Helper::arrayToString($this->getErrors()));
        }
    }

    /**
     * 这里在upload中定义了上传目录根目录别名，以及图片域名
     * 将/var/www/html/advanced/frontend/web/uploads/20160626/file.png 转化为 http://statics.gushanxia.com/uploads/20160626/file.png
     * format:http://domain/path/file.extension
     * @param $filePath
     * @param $field
     * @return string
     */
    private function parseImageUrl($filePath,$field)
    {
        if (strpos($filePath, $this->uploadPathMap[$field]) !== false) {
            return Yii::$app->request->hostInfo .'/uploads/'. $this->uploadPathMap[$field] . str_replace(Yii::getAlias('@uploadPath').'/'.$this->uploadPathMap[$field], '', $filePath);
        }
        return $filePath;
    }
}