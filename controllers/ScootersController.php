<?php


namespace app\controllers;


use app\classes\responses\ScooterResponse;
use app\classes\Scooter;
use app\classes\Storage;
use app\components\BaseController;
use app\exceptions\ScooterNotFoundException;

class ScootersController extends BaseController
{
    public function actionList()
    {
        return Storage::$scooters;
    }

    public function actionUnlock($number) {
        $scooter = Storage::getScooter($number);

        if ($scooter) {
            $response = new ScooterResponse();
            if ($scooter->state == Scooter::STATE_LOCKED)
                $response->result = ScooterResponse::RESULT_OK;
            else
                $response->result = ScooterResponse::RESULT_REFUSED;

            return $response;

        } else {
            throw new ScooterNotFoundException("Scooter $number not found");
        }
    }

    public function actionPause($number) {
        $scooter = Storage::getScooter($number);

        if ($scooter) {
            $response = new ScooterResponse();
            if ($scooter->state == Scooter::STATE_UNLOCKED && $scooter->state != Scooter::STATE_PAUSED)
                $response->result = ScooterResponse::RESULT_OK;
            else
                $response->result = ScooterResponse::RESULT_REFUSED;

            return $response;

        } else {
            throw new ScooterNotFoundException("Scooter $number not found");
        }
    }

    public function actionResume($number) {
        $scooter = Storage::getScooter($number);

        if ($scooter) {
            $response = new ScooterResponse();
            if ( $scooter->state == Scooter::STATE_PAUSED)
                $response->result = ScooterResponse::RESULT_OK;
            else
                $response->result = ScooterResponse::RESULT_REFUSED;

            return $response;

        } else {
            throw new ScooterNotFoundException("Scooter $number not found");
        }
    }

}