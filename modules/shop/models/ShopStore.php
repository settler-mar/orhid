<?php

namespace app\modules\shop\models;

use Yii;
use karpoff\icrop\CropImageUploadBehavior;
use JBZoo\Image\Image;
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
                'ratio' => 220/160,
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
            [['title', 'description', 'price'], 'required'],
            [['description'], 'string'],
            [['price'], 'number'],
            [['active'], 'integer'],
            [['created_at'], 'safe'],
            [['title', 'comment'], 'string', 'max' => 255],
            [['picture'], 'image',
                'minHeight' => 500,
                'skipOnEmpty' => true,
                'maxSize' => 1024*1024*2,
            ],
            [['title'], 'unique'],
        ];
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            if($this->isNewRecord){
                $this->setAttribute('created_at',time());
            }
            $this->setAttribute('update_at',time());
            return  true;
        } else {
            return false;
        }
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
            'comment' => 'Comment (for admin)',
            'active' => 'Active',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     * Сохраняем изображения после сохранения
     * данных пользователя
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->saveImage();
    }

    /**
     * Сохранение изображения (аватара)
     * пользвоателя
     */
    public function saveImage()
    {
        $picture = \yii\web\UploadedFile::getInstance($this, 'picture');
        if ($picture) {

            $path = 'img/shop_item/';// Путь для сохранения аватаров
            $oldImage = $this->picture;

            $name = time() . '-' . $this->id; // Название файла
            $exch = explode('.', $picture->name);
            $exch = $exch[count($exch) - 1];
            $name .= '.' . $exch;
            $this->picture = $path . $name;   // Путь файла и название
            if (!file_exists($path)) {
                mkdir($path, 0777, true);   // Создаем директорию при отсутствии
            }

            $request = Yii::$app->request;
            $post = $request->post();

            $class = $this::className();
            $class = str_replace('\\', '/', $class);
            $class = explode('/', $class);
            $class = $class[count($class) - 1];
            $cropParam = array();
            if (isset($post[$class])) {
                $cropParam = explode('-', $post[$class]['picture']);
            }
            if (count($cropParam) != 4) {
                $cropParam = array(0, 0, 100, 100);
            }

            $img = (new Image($picture->tempName));
            $imgWidth = $img->getWidth();
            $imgHeight = $img->getHeight();


            $cropParam[0] = (int)($cropParam[0] * $imgWidth / 100);
            $cropParam[1] = (int)($cropParam[1] * $imgHeight / 100);
            $cropParam[2] = (int)($cropParam[2] * $imgWidth / 100);
            $cropParam[3] = (int)($cropParam[3] * $imgHeight / 100);

            $img->crop($cropParam[0], $cropParam[1], $cropParam[2], $cropParam[3])
                ->fitToWidth(500)
                ->saveAs($this->picture);


            if ($img) {
                $this->removeImage($oldImage);   // удаляем старое изображение

                $this::getDb()
                    ->createCommand()
                    ->update($this->tableName(), ['picture' => $this->picture], ['id' => $this->id])
                    ->execute();
            }
        }
    }

    /**
     * Удаляем изображение при его наличии
     */
    public function removeImage($img)
    {
        if ($img) {
            // Если файл существует
            if (file_exists($img)) {
                unlink($img);
            }
        }
    }
}
