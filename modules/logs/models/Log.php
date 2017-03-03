<?php

namespace app\modules\logs\models;

use Yii;

/**
 * This is the model class for table "log".
 *
 * @property int $id
 * @property int $type
 * @property int $action
 * @property int $user_id
 * @property int $admin_id
 * @property int $created_at
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'action', 'user_id', 'admin_id', 'created_at'], 'required'],
            [['type', 'action', 'user_id', 'admin_id', 'created_at'], 'integer'],
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
            'action' => 'Action',
            'user_id' => 'User ID',
            'admin_id' => 'Admin ID',
            'created_at' => 'Created At',
        ];
    }
}
