<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\staticPages\models\StaticPages */

$this->title = 'Update Static Pages: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Static Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="static-pages-update">

    <?= $this->render('_form', [
        'model' => $model,
        'canCreate' => $canCreate
    ]) ?>

</div>
