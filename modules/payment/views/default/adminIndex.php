<?php
use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\payment\models\PaymentFilterForm;

$this->title = 'Admin Payments Lists';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('paymentFilterForm', ['model' => $searchModel]);?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    //'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute'=> 'client_id',
            'content' => function($data){
               $user=$data->user;
               return $user->first_name.' '.$user->last_name.'('.$user->id.')';
            },],
        ['attribute'=> 'create_time',
            'content' => function($data){
                return  date("j-M-Y H:i", $data->create_time);
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
      'code',
      'comment'
    ],
]); ?>

