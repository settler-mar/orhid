<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\staticPages\models\StaticPages */

$this->title = 'Update Static Pages: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Static Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="static-pages-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
