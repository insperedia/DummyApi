<?php


namespace app\controllers;


use app\components\BaseController;
use app\exceptions\ApiException;

class SystemController extends BaseController
{
    public function actionError()
    {
        if (($exception = \Yii::$app->getErrorHandler()->exception) === NULL) {
            return '';
        }

        return [
            "error" => get_class($exception),
            "message" =>  $exception->getMessage()
        ];
    }
}