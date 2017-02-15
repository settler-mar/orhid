<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\Payments */

$this->title = 'Payment successful';
$this->params['breadcrumbs'][] = $this->title;

d($user);
?>

<p>
  Спасибо, <?=$user->first_name;?> <?=$user->last_name;?>.
</p>

<p>
  От вас поступила оплата на сумму $<?=$tariff->price;?> за пакет <?=$tariff->name;?>
</p>

<p>
  Для просмотра баланса перейдите по <a href="/user/balance">ссылке</a>
</p>
