<?php

namespace app\components\behaviors;

use app\components\Formatter;
use Yii;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\db\ColumnSchema;

class DecimalColumnsBehavior extends Behavior
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
            if ($this->isDecimalColumn($column)) {
                $this->owner->{$column->name} = $formatter->asDecimal($this->owner->{$column->name});
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

            if ($this->isDecimalColumn($column) && $this->isDecimalBRFormat($column)) {
                $this->owner->{$column->name} = $formatter->asDecimalUS($this->owner->{$column->name});
            }
        }
    }

    /**
     * Metodo que verifica se a coluna informada e do tipo Double/Decimal
     * @param ColumnSchema $column
     * @return bool
     */
    private function isDecimalColumn(ColumnSchema $column)
    {
        return in_array($column->type, ['decimal', 'double', 'float', 'real', 'numeric']);
    }

    /**
     * @param ColumnSchema $column
     * @return bool
     */
    private function isDecimalBRFormat(ColumnSchema $column)
    {
        return (strstr($this->owner->{$column->name}, ',') !== false);
    }
}
