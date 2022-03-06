<?php

namespace api\traits;

use api\exceptions\HttpBadRequestException;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

trait Uploads
{
    /**
     * @Notes: 上传图片
     * @Function actionUpload
     * @return array
     * @throws \Exception
     * @author: Admin
     * @Time: 2022/2/21 15:56
     */
    public function actionUpload()
    {
        try {
            $field = Yii::$app->request->get('field', ''); // 上传字段
            $type  = Yii::$app->request->get('scope', ''); // 模型类型
            $namespace = "\\api\\models\\forms";
            $classname = $namespace.'\\'.ucfirst($type).'Form';
            if (empty($field)) throw new HttpBadRequestException('缺少 “field” 字段');
            if (empty($type)) throw new HttpBadRequestException('缺少 “scope” 字段');
            if (!class_exists($classname)) throw new HttpBadRequestException($classname.'类不存在');
            $model = new $classname();
            /* @var $model Model */
            if (ArrayHelper::getValue($model->scenarios(), $type)) $model->scenario = $type;
            if (key($_FILES) == 'file') $type = 'file';
            $uploadFile = $model->$field = UploadedFile::getInstanceByName($type);
            if (empty($uploadFile))  throw new HttpBadRequestException('没有发现文件');
            return $this->success([
                'src'  => $model->upload($uploadFile,$field)
            ]);
        } catch (HttpBadRequestException $exception) {
            return $this->error($exception->getMessage());
        }
    }

    /**
     * @Notes: 删除图片
     * @Function actionDeleteImage
     * @return array
     * @author: Admin
     * @Time: 2022/2/21 15:56
     */
    public function actionDeleteImage()
    {
        $path = Yii::$app->request->post('filePath', '');
        if (empty($path)) return $this->error();
        $path = str_replace(Yii::$app->request->hostInfo.'/uploads', '', $path);
        $path = Yii::getAlias('@uploadPath') . $path;
        if (file_exists($path)) @unlink($path);
        return $this->success($path);
    }
}
