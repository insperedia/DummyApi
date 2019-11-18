<?php

namespace app\controllers;


use app\classes\responses\AuthResponse;
use app\classes\responses\Response;
use app\classes\Storage;
use app\components\BaseController;
use app\models\User;

class AuthController extends BaseController
{
    protected function verbs()
    {
        return [

            'login' => ['POST'],
            'register' => ['POST'],
        ];
    }


    public function actionLogin()
    {
        $username = \Yii::$app->request->post("username");
        $password = \Yii::$app->request->post("password");
        $user = User::findByUsername($username);

        $response = new AuthResponse();
        if ($user) {
            if ($user->password == $password) {
                $response->result = AuthResponse::RESULT_OK;
                $response->token = $user->token;
            } else {
                $response->result = AuthResponse::NOT_AUTHENTICATED;
            }
        } else {
            $response->result = AuthResponse::RESULT_NOT_REGISTERED;
        }
        return $response;
    }

    public function actionSendRestore()
    {
        $response = new Response();
        $response->result = AuthResponse::RESULT_OK;
        return $response;
    }

    public function actionRestore($code)
    {
        $response = new Response();
        $response->result = AuthResponse::RESULT_OK;
        return $response;
    }

    public function actionRegister()
    {
        $username = \Yii::$app->request->post("username");
        $password = \Yii::$app->request->post("password");

        $user = User::findByUsername($username);

        $response = new AuthResponse();

        if ($user) {
            $response->result = AuthResponse::RESULT_USER_EXISTS;
        } else {
            $newUser = new User();
            $newUser->password = $password;
            $newUser->username = $username;
            $newUser->id = max(array_keys(Storage::$users)) + 1;
            $newUser->token = "token".$newUser->id;
            Storage::$users[$newUser->id] = (array)$newUser->attributes;
            Storage::save();
            $response->result = AuthResponse::RESULT_REGISTERED;
            $response->token = $newUser->token;
        }
        return $response;
    }
}