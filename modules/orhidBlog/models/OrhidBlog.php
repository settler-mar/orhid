<?php

namespace app\modules\orhidBlog\models;

//use GuzzleHttp\Psr7\UploadedFile;
use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "orhid_blog".
 *
 * @property integer $id
 * @property string $title
 * @property string $annotation
 * @property string $text
 * @property string $image
 * @property integer $language
 * @property integer $state
 */
class OrhidBlog extends \yii\db\ActiveRecord
{
    public $string;
    public $imageTmp;
    public $filename;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orhid_blog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'annotation', 'text', 'language', 'state'], 'required'],
            [['language', 'state'], 'integer'],
            [['title'], 'string', 'max' => 64],
            [['annotation'], 'string', 'max' => 256],
            [['text'], 'string', 'max' => 255],
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
            'annotation' => 'Annotation',
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
            $this->filename = 'img/orhidblog/' . $this->string . '.' . $this->imageTmp->extension;
            $this->imageTmp->saveAs($this->filename);
            $this->image = '' . $this->filename;
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}
