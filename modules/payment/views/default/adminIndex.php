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
        ['attribute'=> 'status',
            'content' => function($data){
                return $data::statusText($data->status);
            },],
        'client_id',
        'price',
        ['attribute'=> 'method',
            'content' => function($data){
                return $data::MethodPayText($data->method);
            },],
        ['class' => 'yii\grid\ActionColumn',
            'template' => '{view}{update}',],
    ],
]); ?>

