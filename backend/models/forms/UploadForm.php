<?php

namespace backend\models\forms;

use yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Class UploadForm 上传文件处理类
 *
 * @package app\models\forms
 */
class UploadForm extends Model
{
    /**
     * @var UploadedFile 定义上传字段
     */
    public $avatar;

    public $thumb;

    /** @var string 店铺logo */
    public $logo;

    /** @var string 身份证正面 */
    public $id_card_front;

    /** @var string 身份证反面 */
    public $id_card_side;

    /**
     * 设置应用场景
     *
     * @return array
     */
    public function scenarios()
    {
        return [
            // 场景名称和字段名称一致
            'avatar' => ['avatar'],
            'thumb' => ['thumb'],
            'logo' => ['logo'],
            'id_card_front' => ['id_card_front'],
            'id_card_side' => ['id_card_side'],
        ];
    }

    // 验证规则
    public function rules()
    {
        return [
            // 定义字段的验证规则，注意需要定义场景
            [['avatar'], 'image', 'extensions' => ['png', 'jpg', 'gif', 'jpeg'], 'on' => 'avatar'],
            [['thumb'], 'image', 'extensions' => ['png', 'jpg', 'gif', 'jpeg'], 'on' => 'thumb'],
            [['logo'], 'image', 'extensions' => ['png', 'jpg', 'gif', 'jpeg'], 'on' => 'logo'],
            [['id_card_front'], 'image', 'extensions' => ['png', 'jpg', 'gif', 'jpeg'], 'on' => 'id_card_front'],
            [['id_card_side'], 'image', 'extensions' => ['png', 'jpg', 'gif', 'jpeg'], 'on' => 'id_card_side'],
        ];
    }
}