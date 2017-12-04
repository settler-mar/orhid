<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\Payments */

$this->title = 'Payment successful';
$this->params['breadcrumbs'][] = $this->title;

?>

<p>
  Спасибо, <?=$user->first_name;?> <?=$user->last_name;?>.
</p>

<p>
  От вас поступила оплата на сумму $<?=$order->price;?> за <?=$order->name;?>
</p>

<p>
  Для просмотра баланса перейдите по <a href="/payment">ссылке</a>
</p>
