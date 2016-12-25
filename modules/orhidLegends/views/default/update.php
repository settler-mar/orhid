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

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
