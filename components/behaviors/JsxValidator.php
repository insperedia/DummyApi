<?php

namespace app\components\behaviors;

use app\models\User;
use Yii;
use yii\base\ActionFilter;
use yii\web\UnauthorizedHttpException;

class JsxValidator extends ActionFilter
{
    public function beforeAction($action)
    {
        $authorizationHeader = Yii::$app->request->getHeaders()['authorization'];
        $token = explode(' ', $authorizationHeader)[1];
        $part = explode(".", $token);

        $header = $part[0];
        $payload = $part[1];
        $signature = $part[2];

        $valid = base64_encode(hash_hmac('sha256', "{$header}.{$payload}", User::JWT_SECRET_ASSIGNATURE, true));

        if ($signature !== $valid) {
            throw new UnauthorizedHttpException('Your request was made with invalid credentials.');
        }

        return true;
    }
}