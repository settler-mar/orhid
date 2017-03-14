<?php

namespace app\modules\mail\models;

use Yii;

/**
 * This is the model class for table "mail".
 *
 * @property int $id
 * @property int $user_from
 * @property int $user_to
 * @property int $is_read
 * @property string $message
 * @property int $created_at
 */
class Mail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_from', 'user_to', 'is_read', 'created_at'], 'integer'],
            [['message'], 'required'],
            [['message'], 'string'],
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
