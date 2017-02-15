<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "payments".
 *
 * @property int $id
 * @property int $type
 * @property int $pos_id
 * @property double $price
 * @property string $code
 * @property int $status
 */
class Payments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'pos_id', 'price', 'code'], 'required'],
            [['type', 'pos_id', 'status','client_id'], 'integer'],
            [['price'], 'number'],
            [['code'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'pos_id' => 'Pos ID',
            'price' => 'Price',
            'code' => 'Code',
            'status' => 'Status',
            'clientId' => 'clientId',
        ];
    }
}
