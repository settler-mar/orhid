<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\tarificator\models\TarificatorTable;
use app\modules\user\models\User;
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
    public static function getTextStatus(){
        return array(
            '' => 'All',
            '0'=>'Text for status 0000',
            '1'=>'Text for status 1111',
            '2'=>'Text for status 2222',
            '3'=>'Text for status 3333'
        );
    }
    public static function statusText($param)
    {
        $textForStatus = Payments::getTextStatus();
        if ($param < count($textForStatus)) return  $textForStatus[$param];
        else return 'Unknown status';
    }
    public static function getTextMethod(){
        return array(
            ''=>'По умолчанию',
            '1'=>'PayPal',
            '2'=>'Card',
            '3'=>'Administrator'
        );
    }
    public static function MethodPayText($param)
    {
        $textForStatus = Payments::getTextMethod();
        if ($param < count($textForStatus)) return  $textForStatus[$param];
        else return 'Unknown pay system';
    }
    public static function tableName()
    {
        return 'payments';
    }

    public function getTarificatorTable()
    {
        return $this->hasOne(TarificatorTable::className(),['id' => 'pos_id']);
    }
    public function getUser()
    {
        return $this->hasOne(User::className(),['id' => 'client_id']);
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
            'client_id' => 'Client',
            'type' => 'Type',
            'pos_id' => 'Tariff',
            'price' => 'Price',
            'method' => 'Method',
            'code' => 'Code',
            'status' => 'Status',
            'clientId' => 'clientId',
        ];
    }
}
