<?php

namespace api\models\forms;

use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use api\exceptions\ParamsException;


class AvatarForm extends Model
{
    const UPLOAD_PATH = 'web/face';

    public $face;

    private $basePath;

    public function init ()
    {
        $this->basePath = Yii::getAlias('@uploadPath') . '/' . self::UPLOAD_PATH . '/' . date("Y-m") . '/' . date("d");
        parent::init();
    }

    /**
     * @Notes: 场景
     * @Function: scenarios
     * @return \string[][]
     * @Author: 17908
     * @Time: 2022/2/24 0024 21:03
     */
    public function scenarios ()
    {
        return [
            'avatar' => [ 'face' ],
            'file' => [ 'face' ],
        ];
    }

    /**
     * @Notes: 验证规则
     * @Function: rules
     * @return array[]
     * @Author: 17908
     * @Time: 2022/2/24 0024 21:04
     */
    public function rules ()
    {
        return [
            // 定义字段的验证规则，注意需要定义场景
            [
                [ 'face' ],
                'file',
                'skipOnEmpty' => false,
                'extensions' => [ 'png', 'jpg', 'gif', 'jpeg', 'bmp' ],
                'on' => 'avatar,file'
            ]
        ];
    }

    /**
     * @Notes: 保存图片
     * @Function: upload
     * @param $objFile
     * @param $field
     * @return string
     * @throws \yii\base\Exception
     * @Author: 17908
     * @Time: 2022/2/25 0025 7:31
     */
    public function upload ( $objFile, $field )
    {
        if (!$this->validate()) throw new ParamsException(arrayToString($this->getErrors()));
        $filePath = $this->basePath . '/' . md5(time()) . '.' . $objFile->extension;
        if (!is_dir($this->basePath) || !is_writable($this->basePath)) FileHelper::createDirectory($this->basePath, 0777, true);
        if (!$objFile->saveAs($filePath)) throw new ParamsException(arrayToString($objFile->getHasError()));
        return $this->parseImageUrl($filePath, $field);
    }

    /**
     * @Notes: 路径转换
     * @Function: parseImageUrl
     * @param $filePath
     * @param $field
     * @return string
     * @Author: 17908
     * @Time: 2022/2/25 0025 7:31
     */
    private function parseImageUrl ( $filePath, $field )
    {
        if (strpos($filePath, self::UPLOAD_PATH) !== false) {
            return Yii::$app->request->hostInfo . '/uploads/' . self::UPLOAD_PATH . str_replace(Yii::getAlias('@uploadPath') . '/' . self::UPLOAD_PATH, '', $filePath);
        }
        return $filePath;
    }
}
