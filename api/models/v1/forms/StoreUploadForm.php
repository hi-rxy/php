<?php
namespace api\models\v1\forms;

use jinxing\admin\helpers\Helper;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\FileHelper;

/**
 * Class StoreUploadForm 店铺图片上传模型
 *
 * @package app\models\forms
 */
class StoreUploadForm extends Model
{
    /** @var string 店铺logo */
    public $logo;

    /** @var string 身份证正面 */
    public $id_card_front;

    /** @var string 身份证反面 */
    public $id_card_side;

    /** @var array  三级目录路径映射*/
    private $uploadPathMap = [];

    /** @var string 二级目录-店铺 */
    private $uploadDir = 'store';

    /**
     * 初始化属性
     */
    public function init()
    {
        parent::init();

        $this->uploadPathMap = [
            'logo'          => $this->uploadDir.'/logo', // 店铺LOGO
            'id_card_front' => $this->uploadDir.'/idCardFront', // 店铺-拥有者身份证正面
            'id_card_side'  => $this->uploadDir.'/idCardSide', // 店铺-拥有者身份证反面
        ];
    }

    /**
     * 设置应用场景
     * @return array|array[]|\string[][]
     */
    public function scenarios()
    {
        return [
            // 场景名称和字段名称一致
            'store'         => ['logo'],
            'id_card_front' => ['id_card_front'],
            'id_card_side'  => ['id_card_side'],
        ];
    }

    /**
     * 验证规则
     * @return array|array[]
     */
    public function rules()
    {
        return [
            // 定义字段的验证规则，注意需要定义场景
            [
                ['logo'],
                'file',
                'skipOnEmpty' => false,
                'extensions' => ['png', 'jpg', 'gif', 'jpeg'],
                'on' => 'store'
            ],
            [
                ['id_card_front'],
                'file',
                'skipOnEmpty' => false,
                'extensions' => ['png', 'jpg', 'gif', 'jpeg'],
                'on' => 'id_card_front'
            ],
            [
                ['id_card_side'],
                'file',
                'skipOnEmpty' => false,
                'extensions' => ['png', 'jpg', 'gif', 'jpeg'],
                'on' => 'id_card_side'
            ],
        ];
    }

    /**
     * 保存图片
     * @param $objFile
     * @param $field
     * @return string
     * @throws Exception
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
                throw new Exception(Helper::arrayToString($objFile->getHasError()));
            }
        } else {
            throw new Exception(Helper::arrayToString($this->getErrors()));
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
