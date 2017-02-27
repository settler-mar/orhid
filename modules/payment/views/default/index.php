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
    <h3>Name</h3>
    <dd><?=$user->first_name ?> <?=$user->last_name ?></dd>
    <h3>Credits</h3>
    <dd><?=$user->credits ?></dd>
    <h3>End date</h3>
    <dd><?=date("Y-m-d H:i:s",$user->tariff_end_date) ?></dd>
</dl>
<?php if ($currentTariff){?>
<div class="payments-list-index">
        <div class="col-sm-6 col-md-4">
            <div class="thumbnail">
                <div class="caption">

                    <dl>
                        <h3>Tariff name</h3>
                        <dt><?= $currentTariff->tarificatorTable->name ?></dt>
                        <h3>Tariff Description</h3>
                        <dt> <?= $currentTariff->tarificatorTable->description ?> </dt>
                        <h3>Tariff price</h3>
                         <dt> <?= $currentTariff->price ?> </dt>
                        <h3>Tariff left data</h3>
                        <div>
                            <?php foreach(json_decode($user->tariff_unit, true) as $key => $arr ) { ?>
                                <div>
                                <?php foreach ($tariffs as $row) {
                                    if ($row['code'] == $key) { ?> <span> <?= $row['description']?> </span> <?php }?>
                                <?php } ?>
                                <span> <?= $arr ?></span>
                                    </div>
                            <?php } ?>


                        </div>
                        <h3>Pay time</h3>
                       <dt> <?= $currentTariff->pay_time ?> </dt>
                    </dl>

                </div>
            </div>

        </div>
</div>
<?php }  else {?>
    <h3>You don't have any Tariff. </h3>
    <h3>You can buy some Tariff. Then on this page will be posted tariff description. </h3>
<?php }?>

</div>
