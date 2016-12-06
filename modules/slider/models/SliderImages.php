<?php

namespace app\modules\slider\models;

use Yii;

/**
 * This is the model class for table "slider_images".
 *
 * @property integer $image_id
 * @property string $address
 * @property string $text
 */
class SliderImages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'slider_images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image_id', 'address', 'text'], 'required'],
            [['image_id'], 'integer'],
            [['address'], 'string', 'max' => 60],
            [['text'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'image_id' => 'Image ID',
            'address' => 'Address',
            'text' => 'Text',
        ];
    }
}
