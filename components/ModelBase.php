<?php

namespace app\components;

use app\components\behaviors\DateTimeColumnsBehavior;
use app\components\behaviors\DecimalColumnsBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

class ModelBase extends ActiveRecord
{
    /**
     * @var string
     */
    protected $idLabel = 'ID';

    /**
     * @var string
     */
    protected $createdAtLabel = 'Created Datetime';

    /**
     * @var string
     */
    protected $updateAtLabel = 'Last Updated';

    /**
     * @var string
     */
    protected $createdByLabel = 'Created By';

    /**
     * @var string
     */
    protected $updatedByLabel = 'Last Updated By';

    /**
     * @var string
     */
    protected $statusLabel = 'Active';

    /**
     * @var string
     */
    private $createdAtAttribute = 'created_at';

    /**
     * @var string
     */
    private $updatedAtAttribute = 'updated_at';

    /**
     * @var string
     */
    private $createdByAttribute = 'created_by';

    /**
     * @var string
     */
    private $updatedByAttribute = 'updated_by';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => DateTimeColumnsBehavior::class
            ],
            [
                'class' => DecimalColumnsBehavior::class
            ],
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => $this->getCreatedAtAttribute(),
                'updatedAtAttribute' => $this->getUpdatedAtAttribute(),
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => $this->getCreatedByAttribute(),
                'updatedByAttribute' => $this->getUpdatedByAttribute()
            ]
        ];
    }

    /**
     * Generic method to assist in assembling the forms dropdown
     * @param string $labelColumn
     * @param string $keyColumn
     * @param string $order
     * @return array
     */
    public static function getDropdownOptions($labelColumn, $keyColumn = 'id', $order = null)
    {
        $list = [];
        $rows = self::find()
                    ->select([$labelColumn, $keyColumn])
                    ->orderBy(!is_null($order) ? $order : "\"{$labelColumn}\" ASC")
                    ->all();

        foreach ($rows as $row) {
            $list[] = [
                'label' => $row[$labelColumn],
                'value' => $row[$keyColumn]
            ];
        }

        return $list;
    }

    /**
     * @return bool|string
     */
    private function getCreatedAtAttribute()
    {
        return (array_key_exists($this->createdAtAttribute, $this->attributes) ? $this->createdAtAttribute : false);
    }

    /**
     * @return bool|string
     */
    private function getUpdatedAtAttribute()
    {
        return (array_key_exists($this->updatedAtAttribute, $this->attributes) ? $this->updatedAtAttribute : false);
    }

    /**
     * @return bool|string
     */
    private function getCreatedByAttribute()
    {
        return (array_key_exists($this->createdByAttribute, $this->attributes) ? $this->createdByAttribute : false);
    }

    /**
     * @return bool|string
     */
    private function getUpdatedByAttribute()
    {
        return (array_key_exists($this->updatedByAttribute, $this->attributes) ? $this->updatedByAttribute : false);
    }
}
