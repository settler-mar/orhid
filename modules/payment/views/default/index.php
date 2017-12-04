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
    <div>
      Name
      <dd><?=$user->first_name ?> <?=$user->last_name ?></dd>
    </div>
    <div>
      Credits
      <dd><?=$user->credits ?></dd>
    </div>
    <?php
      if($user->tariff_end_date>time()) {
        ?>
        <div>
          Active tariff
          <dd><?= $user->tariff_name ?></dd>
        </div>
        <div>
          The tariff paid to
          <dd><?= date("Y-M-d H:i", $user->tariff_end_date) ?></dd>
        </div>
        <?php
          $units=$user->tariffUnits;
          if($units){
        ?>
        </dl>
        <dl>
          <h2>Tariffs units</h2>
          <?php
          foreach ($units as $unit) {
            ?>
            <div>
              <?= $unit['name']; ?>
              <dd><?= $unit['users']; ?>/<?= $unit['start']; ?></dd>
            </div>
            <?php
          }
        }
      }
  ?>
</dl>
<?php if ($dataProvider){?>
<div class="payments-list-index">
  <?= GridView::widget([
    'dataProvider' => $dataProvider,
    //'filterModel' => $searchModel,
    'columns' => [
      ['class' => 'yii\grid\SerialColumn'],
      ['attribute'=> 'create_time',
        'content' => function($data){
          return  date("j-M-Y H:i", $data->create_time);
        },],
      'typeText',
      'detail',
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
    ],
  ]); ?>
</div>
<?php }  else {?>
    <h3>You don't have any payments. </h3>
    <h3>You can buy some tariff, credits or gift. Then on this page will be posted tariff description. </h3>
<?php }?>

</div>
