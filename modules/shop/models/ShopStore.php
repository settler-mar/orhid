<?php

namespace app\modules\shop\models;

use Yii;
use karpoff\icrop\CropImageUploadBehavior;

/**
 * This is the model class for table "shop_store".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $picture
 * @property double $price
 * @property string $comment
 * @property integer $active
 * @property string $created_at
 */
class ShopStore extends \yii\db\ActiveRecord
{

    function behaviors()
    {
        return [
            [
                'class' => CropImageUploadBehavior::className(),
                'attribute' => 'picture',
                'scenarios' => ['insert', 'update'],
                'path' => '@webroot',
                'url' => '@web',
                'ratio' => 230/285,
                /*'crop_field' => 'photo_crop',
                'cropped_field' => 'photo_cropped',*/
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_store';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description', 'price', 'created_at'], 'required'],
            [['description'], 'string'],
            [['price'], 'number'],
            [['active'], 'integer'],
            [['created_at'], 'safe'],
            [['title', 'comment'], 'string', 'max' => 255],
            [['picture'], 'image',
                'minHeight' => 500,
                'skipOnEmpty' => true
            ],
            [['title'], 'unique'],
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
            'description' => 'Description',
            'picture' => 'Picture',
            'price' => 'Price',
            'comment' => 'Comment',
            'active' => 'Active',
            'created_at' => 'Created At',
        ];
    }
}
