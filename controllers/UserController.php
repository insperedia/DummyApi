<?php


namespace app\controllers;


use app\classes\Storage;
use app\components\BaseController;
use app\models\User;

class UserController extends BaseController
{
    public function actionProfile()
    {
        $user = User::GetByAuthToken();
        return $user;
    }

    public function actionHistory() {
        return Storage::$history;
    }
}