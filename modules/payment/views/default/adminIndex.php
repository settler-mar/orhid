<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Admin Payments Lists';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'status',
        'client_id',
        'price',
        ['class' => 'yii\grid\ActionColumn',
            'template' => '{view}{update}',],
    ],
]); ?>

