<?php

namespace app\modules\tariff\models;

use Yii;

/**
 * This is the model class for table "tarifTimerTable".
 *
 * @property integer $id
 * @property double $price
 * @property integer $code
 * @property string $description
 */
class Tariff extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tarifTimerTable';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price', 'code', 'description'], 'required'],
            [['price'], 'number'],
            [['code'], 'string', 'max' => 32],
            [['description'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'price' => 'Price',
            'code' => 'Code',
            'description' => 'Description',
        ];
    }
}
