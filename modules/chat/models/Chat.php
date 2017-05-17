<?php

namespace app\modules\chat\models;

use Yii;

/**
 * This is the model class for table "chat".
 *
 * @property integer $id
 * @property integer $user_from
 * @property integer $user_to
 * @property integer $is_read
 * @property string $message
 * @property integer $created_at
 */
class Chat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'chat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_from', 'user_to', 'is_read', 'created_at'], 'integer'],
            [['message', 'created_at'], 'required'],
            [['message'], 'string', 'max' => 1000],
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
            'is_read' => 'Is Read',
            'message' => 'Message',
            'created_at' => 'Created At',
        ];
    }
}
