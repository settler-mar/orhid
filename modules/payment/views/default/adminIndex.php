<?php
use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\payment\models\PaymentFilterForm;

$this->title = 'Admin Payments Lists';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('paymentFilterForm', ['model' => $filterForm]);?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute'=> 'client_id',
            'content' => function($data){
                return $data->user->first_name.' '.$data->user->last_name.'('.$data->user->id.')';
            },],
        ['attribute'=> 'pay_time',
            'content' => function($data){
                return  date("j-M-Y H:i:s", $data->pay_time);
            },],
        ['attribute'=> 'price',
            'content' => function($data){
                return  number_format($data->price, 2, ',', ' ');
            },],
        ['attribute'=> 'status',
            'content' => function($data){
                return $data::statusText($data->status);
            },],
        ['attribute'=> 'method',
            'content' => function($data){
                return $data::MethodPayText($data->method);
            },],
    ],
]); ?>

