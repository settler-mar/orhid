<?php

namespace app\modules\shop\models;

use Yii;

/**
 * This is the model class for table "shop_order".
 *
 * @property int $id
 * @property int $user_from
 * @property int $user_to
 * @property int $item_id
 * @property double $price
 * @property int $admin
 * @property int $status
 * @property string $user_comment
 * @property string $user_admin
 * @property int $created_at
 */
class ShopOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_from', 'user_to', 'item_id', 'price', 'created_at'], 'required'],
            [['user_from', 'user_to', 'item_id', 'admin', 'status', 'created_at'], 'integer'],
            [['price'], 'number'],
            [['user_comment', 'user_admin'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_from' => 'User From',
            'user_to' => 'User To',
            'item_id' => 'Item ID',
            'price' => 'Price',
            'admin' => 'Admin',
            'status' => 'Status',
            'user_comment' => 'User Comment',
            'user_admin' => 'User Admin',
            'created_at' => 'Created At',
        ];
    }
}
