<?php

namespace api\actions;

use Yii;

class ErrorAction extends \yii\web\ErrorAction
{
    use \api\traits\Response;

    /**
     * Builds string that represents the exception.
     * Normally used to generate a response to AJAX request.
     * @return array
     * @since 2.0.11
     */
    protected function renderAjaxResponse()
    {
        Yii::$app->getResponse()->setStatusCode(200);
        return $this->error($this->getExceptionMessage());
    }

    /**
     * Builds string that represents the exception.
     * Normally used to generate a response to AJAX request.
     * @return array
     * @since 2.0.11
     */
    protected function renderHtmlResponse()
    {
        Yii::$app->getResponse()->setStatusCode($this->getExceptionCode());
        return $this->error($this->getExceptionMessage());
    }
}
