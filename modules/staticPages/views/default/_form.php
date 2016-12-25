<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use dosamigos\tinymce\TinyMce;



/* @var $this yii\web\View */
/* @var $model app\modules\staticPages\models\StaticPages */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="static-pages-form">
    <h1>111</h1>
     <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?=
        $form->field($model, 'text')->widget(TinyMce::className(), [
            'options' => ['rows' => 6],
            'language' => 'ru',
            'clientOptions' => [
                'plugins' => [
                    "image",
                    "responsivefilemanager",
                    "advlist autolink lists link charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste ",
                    "table contextmenu directionality emoticons paste textcolor  code"

                ],
                'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                'toolbar2' => "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
                'image_advtab' => 'true' ,
                  'external_filemanager_path' =>"/filemanager/",
                   'filemanager_title'=> "Responsive Filemanager" ,
                   'external_plugins' => "{ 'filemanager' => '/filemanager/plugin.min.js'}",
            ]
        ]);?>

    <?= $form->field($model, 'language')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
