<?php

namespace app\modules\slider\models;

use Yii;
use yii\web\UploadedFile;
/**
 * This is the model class for table "slider_images".
 *
 * @property integer $image_id
 * @property string $address
 * @property string $text
 * @property string $gender
 */
class SliderImages extends \yii\db\ActiveRecord
{
    public $image;
    public $filename;
    public $string;
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
            [[ 'gender'], 'required'],
            [['text'], 'string', 'max' => 256],
            [['gender'], 'string', 'max' => 10],
            [['address'], 'file','extensions' => 'jpg','skipOnEmpty' => true ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'image_id' => 'Image ID',
            'address' => 'Picture',
            'text' => 'Text',
            'gender' => 'Gender',
        ];
    }

    public function beforeSave($insert)
    {
            $this->string = substr(uniqid('img'),0,12);
            $this->image = UploadedFile::getInstance($this, 'address');
            if ($this->image!=null) {
                $this->filename = 'img/slider/' . $this->string . '.' . $this->image->extension;
                $this->image->saveAs($this->filename);
                $this->address = '' . $this->filename;
            }
            //else return false;
        return parent::beforeSave($insert);
    }
}
