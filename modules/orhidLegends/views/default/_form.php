<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;
use app\components\fileImageInput\FileInput;
use karpoff\icrop\CropImageUpload;
use dosamigos\fileupload\FileUpload;

/* @var $this yii\web\View */
/* @var $model app\modules\orhidLegends\models\OrhidLegends */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orhid-legends-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?=
    $form->field($model, 'text')->widget(TinyMce::className(), [
        'options' => ['rows' => 6],
        'language' => 'ru',
        'clientOptions' => [
            'plugins' => [
                'advlist autolink lists link charmap  print hr preview pagebreak',
                'searchreplace wordcount textcolor visualblocks visualchars code fullscreen nonbreaking',
                'save insertdatetime media table contextmenu template paste image'
            ],
            'toolbar' => 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            'external_filemanager_path' => '/js/tinymce/plugins/responsivefilemanager/filemanager/',
            'filemanager_title' => 'Responsive Filemanager',
            'external_plugins' => [
                //Иконка/кнопка загрузки файла в диалоге вставки изображения.
                'filemanager' => '/js/tinymce/plugins/responsivefilemanager/filemanager/plugin.min.js',
                //Иконка/кнопка загрузки файла в панеле иснструментов.
                //'responsivefilemanager' => 'plugins/responsivefilemanager/tinymce/plugins/responsivefilemanager/plugin.min.js',
                'responsivefilemanager' => '/js/tinymce/plugins/responsivefilemanager/tinymce/plugins/responsivefilemanager/plugin.min.js',
            ],
        ]
    ]);?>

    <?= $form->field($model, 'cover')->widget(CropImageUpload::className(),['options'=>['accept'=>'image/jpeg']]);?>

    <?= $form->field($model, 'video')->widget(FileInput::classname(),['type'=>'video']); ?>

    <button class="multiload">Add images</button>

    <?= $form->field($model, 'language')->dropDownList(['0' => 'English','1' => 'Русский']) ?>

    <?= $form->field($model, 'state')->dropDownList(['0' => 'Не публиковано','1' => 'Опубликовано'])?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<div class="file_insert_model_bg">
  <div class="file_insert_model">
    <div class="close">X</div>
    <div class="title">Select photo</div>
    <div class="photo_list">

    </div>

    <div>
      <div>
        You can upload new file from your PC.
      </div>
      <div class="progress_bar">
        <span></span>
      </div>
      <?= FileUpload::widget([
        'model'=>$model,
        'attribute' => 'image',
        'url' => ['/fileupload/default/upload'], // your url, this is just for demo purposes,
        'options' => ['accept' => 'image/*'],
        'clientOptions' => [
          'maxFileSize' => 2000
        ], // Also, you can specify jQuery-File-Upload events
        //// see: https://github.com/blueimp/jQuery-File-Upload/wiki/Options#processing-callback-options
        'clientEvents' => [
          'fileuploaddone' => 'fileuploaddone',
          'fileuploadfail' => 'fileuploadfail',
          'fileuploadprogress' => 'fileuploadprogress',
          'fileuploadstart' => 'fileuploadstart',
        ],
      ]);?>
    </div>
    <a class="insert_photo">Insert photo</a>
  </div>
</div>


<script>
  $(document).ready(function() {
    $('.multiload').on('click',function() {
      $('.photo_list').addClass('loading');
      $('.photo_list>*').remove()
      $('.file_insert_model_bg .title').hide();
      $('.file_insert_model_bg').show();
      $.post('/fileupload/default/get', function (data) {
        $('.photo_list').removeClass('loading');
        if (data.length > 0) {
          for (i = 0; i < data.length; i++) {
            add_file_to_list(data[i])
          }
        }
      }, 'json');
    });
  });
</script>
