<?php

namespace app\components;

use app\classes\Storage;
use app\components\behaviors\JsxValidator;
use app\models\User;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\rest\ActiveController;
use yii\rest\Controller;
use yii\web\Response;

abstract class BaseController extends Controller
{
    public function init()
    {
        parent::init();
        Yii::$app->response->charset = 'UTF-8';
        Storage::load();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $excepts = ['options', 'login', 'register'];

        unset($behaviors['authenticator']);

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => $excepts
        ];

        /*
        $behaviors['jsxValidator'] = [
            'class' => JsxValidator::class,
            'except' => $excepts
        ];
*/
        $behaviors['contentNegotiator'] = [
            'class' => 'yii\filters\ContentNegotiator',
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ]
        ];

        $behaviors['cors'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Allow-Origin' => ['*'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Max-Age' => 3600,
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Request-Method' => [
                    'GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'
                ],
                'Access-Control-Expose-Headers' => [
                    'X-Pagination-Current-Page',
                    'X-Pagination-Page-Count',
                    'X-Pagination-Per-Page',
                    'X-Pagination-Total-Count'
                ],
            ]
        ];

        return $behaviors;
    }

    public static function formatModelErrors(array $errors)
    {
        $formattedErrors = [];

        foreach ($errors as $columnName => $errorList) {
            $formattedErrors[] = [
                'field' => $columnName,
                'message' => $errorList[0]
            ];
        }

        return $formattedErrors;
    }
}