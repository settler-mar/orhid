<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\orhidLegends\models\OrhidLegends */

$this->title = 'Create Orhid Legends';
$this->params['breadcrumbs'][] = ['label' => 'Orhid Legends', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orhid-legends-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
