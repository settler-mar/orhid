<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\staticPages\models\StaticPages */

$this->title = 'Create Static Pages';
$this->params['breadcrumbs'][] = ['label' => 'Static Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="static-pages-create">

    <?= $this->render('_form', [
        'model' => $model,
        'canCreate' => $canCreate
    ]) ?>

</div>
