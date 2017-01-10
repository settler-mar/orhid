<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\orhidBlog\models\OrhidBlog */

$this->title = 'Create Orhid Blog';
$this->params['breadcrumbs'][] = ['label' => 'Orhid Blogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orhid-blog-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
