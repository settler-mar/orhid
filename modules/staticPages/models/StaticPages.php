<?php

namespace app\modules\staticPages\models;

use Yii;

/**
 * This is the model class for table "static_pages".
 *
 * @property integer $id
 * @property string $name
 * @property string $text
 */
class StaticPages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'static_pages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'title','index','url'], 'required'],
            [['name'], 'string', 'max' => 32],
            [['title'], 'string', 'max' => 50],
            [['meta_title'], 'string', 'max' => 50],
            [['keywords'], 'string', 'max' => 200],
            [['description'], 'string', 'max' => 500],
            [['text','url'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'text' => 'Text',
            'title' => 'Title',
            'meta_title' => 'Meta title',
            'keywords' => 'Keywords',
            'description' => 'Description',
            'index' => 'Index',

        ];
    }
}
