<?php

namespace app\classes;

class Storage
{
    public static $users;
    public static $history;
    public static $stations;
    /**
     * @var Scooter[]
     */
    public static $scooters;


    private static $_storagePath = __DIR__."/../data/";

    public static function load ()
    {
        self::$users  = require self::$_storagePath."users.php";
        self::$history  = require self::$_storagePath."history.php";
        self::$stations  = require self::$_storagePath."stations.php";
        self::$scooters  = require self::$_storagePath."scooters.php";
        $scooter = \Yii::createObject(\app\classes\Scooter::class, [
            'id' => 0,
            'number' => '000000',
            'state' => \app\classes\Scooter::STATE_UNLOCKED
        ]);
    }

    public static function save() {
        $dump = var_export(self::$users, true);
        file_put_contents(self::$_storagePath."users.php", "<?php return ".$dump.";");

/*
        $dump = var_export(self::$stations, true);
        file_put_contents(self::$_storagePath."stations.php", "<?php return ".$dump.";");

        $dump = var_export(self::$history, true);
        file_put_contents(self::$_storagePath."history.php", "<?php return ".$dump.";");
*/

    }

    /**
     * @param string $number
     * @return Scooter|null
     */
    public static function getScooter($number)
    {
        foreach (self::$scooters as $scooter) {
            if ($scooter->number == $number)
                return $scooter;
        }
        return null;
    }
}