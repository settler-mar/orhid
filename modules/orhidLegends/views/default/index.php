<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\orhidLegends\models\OrhidLegendsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orhid Legends';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orhid-legends-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Orhid Legends', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="row">
        <?php foreach ($dataProvider->models as $arr) { ?>
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <img src="<?=$arr->image?>" alt="...">
                    <div class="caption">
                        <h3> <?=$arr->title?></h3>
                        <p> <?=$arr->text?></p>
                        <p><a href="orhidLegends/default/view?id=<?=$arr->id?>" class="btn btn-primary">Legend</a></p>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
