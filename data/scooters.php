<?php

return [

  Yii::createObject( [
      'class' => \app\classes\Scooter::class,
      'id' => 0,
      'number' => '000000',
      'state' => \app\classes\Scooter::STATE_LOCKED
  ]),
    Yii::createObject( [
        'class' => \app\classes\Scooter::class,
        'id' => 1,
        'number' => '000001',
        'state' => \app\classes\Scooter::STATE_LOCKED
    ]),
];