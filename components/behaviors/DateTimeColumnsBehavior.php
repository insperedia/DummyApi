<?php

namespace app\components\behaviors;

use app\components\Formatter;
use Yii;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\db\ColumnSchema;

class DateTimeColumnsBehavior extends Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'afterFindEvent',
            ActiveRecord::EVENT_BEFORE_INSERT => 'insertUpdateEvent',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'insertUpdateEvent',
        ];
    }

    public function afterFindEvent(Event $event)
    {
        /** @var Formatter $formatter */
        $formatter = Yii::$app->formatter;
        $tableColumns = $this->owner->getTableSchema()->columns;

        /** @var ColumnSchema $column */
        foreach ($tableColumns as $column) {
            if ($this->isDateColumn($column)) {
                if (!is_null($this->owner->{$column->name}) || !empty($this->owner->{$column->name})) {
                    $this->owner->{$column->name} = $formatter->asDate($this->owner->{$column->name});
                }
            }

            if ($this->isDateTimeColumn($column)) {
                if (!is_null($this->owner->{$column->name}) || !empty($this->owner->{$column->name})) {
                    $this->owner->{$column->name} = $formatter->asDatetime($this->owner->{$column->name});
                }
            }
        }
    }

    public function insertUpdateEvent(Event $event)
    {
        /** @var Formatter $formatter */
        $formatter = Yii::$app->formatter;
        $tableColumns = $this->owner->getTableSchema()->columns;

        /** @var ColumnSchema $column */
        foreach ($tableColumns as $column) {
            if (empty($this->owner->{$column->name})) {
                continue;
            }

            if ($this->isDateColumn($column)) {
                $this->owner->{$column->name} = $formatter->asDateUS($this->owner->{$column->name}, 'yyyy-MM-dd');
            }

            if ($this->isDateTimeColumn($column)) {
                $value = ($this->owner->{$column->name} == 'NOW()' ? date('Y-m-d H:i:s') : $this->owner->{$column->name});

                if ($this->isDateOrDatetimeBRFormat($column)) {
                    $explodeDate = explode('/', $this->owner->{$column->name});
                    $explodeYear = explode(' ', $explodeDate[2]);
                    $value = $explodeYear[0] . '-' . $explodeDate[1] . '-' . $explodeDate[0] . ' ' . $explodeYear[1];
                }

                $this->owner->{$column->name} = $formatter->asDatetime($value, 'yyyy-MM-dd HH:mm:ss');
            }
        }
    }

    /**
     * @param ColumnSchema $column
     * @return bool
     */
    private function isDateColumn(ColumnSchema $column)
    {
        return in_array($column->type, ['date']);
    }

    /**
     * @param ColumnSchema $column
     * @return bool
     */
    private function isDateTimeColumn(ColumnSchema $column)
    {
        return in_array($column->type, ['datetime', 'timestamp']);
    }

    /**
     * @param ColumnSchema $column
     * @return bool
     */
    private function isDateOrDatetimeBRFormat(ColumnSchema $column)
    {
        return (strstr($this->owner->{$column->name}, '/') !== false);
    }
}
