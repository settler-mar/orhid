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

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php  if ($canCreate) { ?>
        <p><?= Html::a('Create Orhid Legend', ['create'], ['class' => 'btn btn-success']) ?>  </p>
    <?php } ?>
    <div class="row">
        <?php foreach ($dataProvider->models as $arr) { ?>
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <img src="<?=$arr->image?>" alt="...">
                    <div class="caption">
                        <h3> <?=$arr->title?></h3>
                        <p> <?=$arr->text?></p>
                        <?php  if ($canUpdate) { ?>
                            <p><?= Html::a('Update', ['update', 'id' => $arr->id], ['class' => 'btn btn-primary']) ?>  </p>
                        <?php } ?>
                        <?php  if ($canDelete) { ?>
                            <p><?= Html::a('Delete', ['delete', 'id' => $arr->id], [
                                    'class' => 'btn btn-danger',
                                    'data' => [
                                        'confirm' => 'Are you sure you want to delete this item?',
                                        'method' => 'post',
                                    ],
                                ]) ?>  </p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
