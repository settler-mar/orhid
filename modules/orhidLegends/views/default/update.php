<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\orhidLegends\models\OrhidLegends */

$this->title = 'Update Orhid Legends: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Orhid Legends', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="orhid-legends-update">

    <?= $this->render('_form', [
        'model' => $model,
        'fileUpload' => $fileUpload,
    ]) ?>

</div>
