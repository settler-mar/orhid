<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "payments".
 *
 * @property int $id
 * @property int $type
 * @property int $pos_id
 * @property int $client_id
 * @property double $price
 * @property string $code
 * @property int $status
 * @property int $pay_time
 * @property int $create_time
 * @property int $method
 */
class PaymentFilterForm extends Payments
{
    public $pay_time_to;
    public $user_id;
    /**
     * @inheritdoc
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['method','status', 'pay_time', 'pay_time_to','client_id'], 'safe'],
        ];
    }
}
