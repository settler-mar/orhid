<?php
namespace app\modules\fileupload\models;

use yii\base\Model;
use yii\web\UploadedFile;
use JBZoo\Image\Image;


class Fileupload extends Model
{
  /**
   * @var UploadedFile
   */
  public $image;
  public $imagePath;
  public $legend;

  public function rules()
  {
    return [
      [['image'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
    ];
  }

  public function __construct($search=null, $config = [])
  {
    if ($search != null){
      $this->legend = $search;
    }
    parent::__construct($config);
  }

  public function upload($id=null)
  {
    if ($this->validate()) {
      if ($id!=null){
        $path = 'legends_files/'.$id.'/';
      }else {
        $path = \Yii::$app->user->identity->userDir . 'upload/';
      }
      if (!file_exists($path)) {
        mkdir($path, 0777, true);   // Создаем директорию при отсутствии
      }

      $this->imagePath=explode('\\',$this->image->tempName);
      $this->imagePath=explode('.', $this->imagePath[count($this->imagePath)-1])[0];;
      $this->imagePath= $path.$this->imagePath . '.' . $this->image->extension;

      $img = (new Image($this->image->tempName));
      $imgWidth = $img->getWidth();
      $imgHeight = $img->getHeight();

      //$img->fitToWidth()

      if($imgWidth>$imgHeight){
        if($imgWidth>1000){
          $img->fitToWidth(1000);
        }
      }else{
        if($imgHeight>1000){
          $img->fitToHeight(1000);
        }
      }
      $img->saveAs($this->imagePath);

      //$this->image->saveAs($this->imagePath);
      return true;
    } else {
      return false;
    }
  }

  public function getImageUrl(){
    return $this->imagePath;
  }
}