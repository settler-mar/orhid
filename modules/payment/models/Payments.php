<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\tarificator\models\TarificatorTable;
/**
 * This is the model class for table "payments".
 *
 * @property int $id
 * @property int $type
 * @property int $client_id
 * @property int $pos_id
 * @property double $price
 * @property string $code
 * @property int $status
 */
class Payments extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const TIME_OUT = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payments';
    }

    public function getTarificatorTable()
    {
        return $this->hasOne(TarificatorTable::className(),['id' => 'pos_id']);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'pos_id', 'price', 'code','client_id'], 'required'],
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
            'pos_id' => 'Tariff',
            'price' => 'Price',
            'code' => 'Code',
            'status' => 'Status',
            'clientId' => 'clientId',
        ];
    }
}