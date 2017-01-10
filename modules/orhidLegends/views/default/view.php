<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\orhidLegends\models\OrhidLegends */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Orhid Legends', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orhid-legends-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'text',
            'image',
            'language',
            'state',
        ],
    ]) ?>

</div>
