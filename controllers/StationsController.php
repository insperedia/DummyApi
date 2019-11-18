<?php


namespace app\controllers;


use app\classes\Storage;
use app\components\BaseController;

class StationsController extends BaseController
{
    public function actionList()
    {
        return Storage::$stations;
    }
}