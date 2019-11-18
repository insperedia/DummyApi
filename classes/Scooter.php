<?php


namespace app\classes;


use yii\base\BaseObject;

class Scooter extends BaseObject
{
    const STATE_UNLOCKED = "unlocked";
    const STATE_LOCKED = "locked";
    const STATE_PAUSED = "paused";

    var $id;
    var $number;
    var $state;
}