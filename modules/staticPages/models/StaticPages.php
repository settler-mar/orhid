<?php

namespace app\modules\staticPages\models;

use Yii;

/**
 * This is the model class for table "static_pages".
 *
 * @property integer $id
 * @property string $name
 * @property string $text
 * @property integer $language
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
            [['name', 'text', 'language'], 'required'],
            [['language'], 'integer'],
            [['name'], 'string', 'max' => 32],
            [['text'], 'string'],
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
            'language' => 'Language',
        ];
    }
}
