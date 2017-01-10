<?php

namespace app\modules\orhidLegends\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "orhid_legends".
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property string $image
 * @property integer $language
 * @property integer $state
 */
class OrhidLegends extends \yii\db\ActiveRecord
{
    public $string;
    public $imageTmp;
    public $filename;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orhid_legends';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'text', 'language', 'state'], 'required'],
            [['language', 'state'], 'integer'],
            [['title'], 'string', 'max' => 64],
            [['text'], 'string'],
            [['image'], 'file'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'text' => 'Text',
            'image' => 'Image',
            'language' => 'Language',
            'state' => 'State',
        ];
    }

    public function beforeSave($insert){
        $this->string = substr(uniqid('img'),0,12);
        $this->imageTmp = UploadedFile::getInstance($this, 'image');
        if ($this->imageTmp!=null) {
            if (!is_dir('img/orhidlegends/')) mkdir('img/orhidlegends/');
            $this->filename = 'img/orhidlegends/' . $this->string . '.' . $this->imageTmp->extension;
            if ($this->image!= null) $this->imageTmp->saveAs($this->image);
            else
            {
                $this->imageTmp->saveAs($this->filename);
                $this->image = '' . $this->filename;
            }
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}
