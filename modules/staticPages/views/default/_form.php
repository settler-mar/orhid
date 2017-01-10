<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;



/* @var $this yii\web\View */
/* @var $model app\modules\staticPages\models\StaticPages */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="static-pages-form">
     <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

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

    <?= $form->field($model, 'language')->dropDownList(['0' => 'English','1' => 'Русский']) ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'meta_title')->textInput(['maxlength' => true])->label("Мета тайтл") ?>
    <?= $form->field($model, 'keywords')->textInput(['maxlength' => true])->label("Ключевые слова") ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 5, 'cols' => 5])->label("Мета Дескрипшин") ?>
    <?= $form->field($model, 'index')->checkbox(['label' => 'Индексировать страницу'])->label("Управление URL") ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
