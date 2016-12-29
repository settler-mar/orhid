<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\orhidBlog\models\OrhidBlog */

$this->title = 'Update Orhid Blog: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Orhid Blogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="orhid-blog-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
