<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\Payments */

$this->title = 'Payment successful';
$this->params['breadcrumbs'][] = $this->title;

?>

<p>
  Thank you, <?=$user->first_name;?> <?=$user->last_name;?>.
</p>

<p>
  You received a payment of $<?=$order->price;?> за <?=$order->name;?>
</p>

<p>
  To view the balance, click on the <a href="/payment">link</a>
</p>
