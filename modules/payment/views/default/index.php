<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\payment\models\PaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payments Lists';
$this->params['breadcrumbs'][] = $this->title;
?>
<dl>
    <dt>Name</dt>
    <dd><?=$user->first_name ?> <?=$user->last_name ?></dd>
    <dt>Credits</dt>
    <dd><?=$user->credits ?></dd>
    <dt>Tariff</dt>
    <dd><?=$user->tariff_unit ?></dd>
    <dt>Date</dt>
    <dd><?=date("Y-m-d H:i:s",$user->tariff_end_date) ?></dd>
</dl>
<div class="payments-list-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'pos_id',
            'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
