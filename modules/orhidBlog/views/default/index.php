<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\orhidBlog\models\OrhidBlogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

function crop_str($string, $limit, $after = '')
{
    if (strlen($string) > $limit) {
        $substring_limited = substr($string, 0, $limit); //режем строку от 0 до limit
        return  substr($substring_limited, 0, strrpos($substring_limited, ' ')) . $after;
    } else
        return  $string;
}

$this->title = 'Orhid Blogs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orhid-blog-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php  if ($canCreate) { ?>
                 <p><?= Html::a('Create Orhid Blog', ['create'], ['class' => 'btn btn-success']) ?>  </p>
    <?php } ?>


    <div class="row">
     <?php foreach ($dataProvider->models as $arr) { ?>
        <div class="col-sm-12 col-md-12 <?php ($arr->state==0) ? print_r('published_no') : ('') ?>">
            <div class="thumbnail">
                <img src="<?=$arr->image?>" alt="...">
                <div class="caption">
                    <h3> <?=$arr->title?></h3>
                    <p> <?=crop_str(strip_tags($arr->text),150,'...')?></p>
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
