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
<h1> <?= $model->id ?></h1>
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

    <?= $form->field($model, 'cover')->widget(CropImageUpload::className(),['options'=>['accept'=>'image/jpeg'], 'value'=> $model->cover]);?>

    <?= $form->field($model, 'cover_del')->hiddenInput(['class' => 'cover_del'])->label(false) ?>

    <?= $form->field($model, 'video')->widget(FileInput::classname(),['type'=>'video']); ?>
    <?php if ($model->video) {?>
      <a class="del_video">Delete current video </a>
      <span class="text_for_video"></span> <?php
    } ?>
    <?= $form->field($model, 'video_del')->hiddenInput(['class' => 'video_del'])->label(false) ?>
    <a class="multiload">Add images</a>

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
        'model'=>$fileUpload,
        'attribute' => 'image',
        'url' =>'/fileupload/default/upload/?id='.$model->id, // your url, this is just for demo purposes,
        'options' => ['accept' => 'image/*'],
        'clientOptions' => [
          'maxFileSize' => 2000,
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

<?php echo "
<script>
  $(document).ready(function() {
    $('.del_video').on('click', function(){
      $('.text_for_video').text('Video file will delete');
      $('.video_del').val(1);
    })    
    $('.del_cover').on('click', function(){
      $('.text_for_cover').text('Cover file will delete');
      $('.cover_del').val(1);
    })
    
    $('.multiload').on('click',function() {
      $('.photo_list').addClass('loading');
      $('.photo_list>*').remove();
      $('.file_insert_model_bg .title').hide();
      $('.file_insert_model_bg').show();
      $('.insert_photo').hide();
      data={
        legend:  ".$model->id."
      };
      $.post('/fileupload/default/get', data, function (data) {
        console.log(data);
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
"; ?>
