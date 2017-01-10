<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\staticPages\models\StaticPagesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

function crop_str($string, $limit, $after = '')
{
    if (strlen($string) > $limit) {
        $substring_limited = substr($string, 0, $limit); //режем строку от 0 до limit
        return  substr($substring_limited, 0, strrpos($substring_limited, ' ')) . $after;
    } else
        return  $string;
}

$this->title = 'Static Pages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="static-pages-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php  if ($canCreate) { ?>
        <p><?= Html::a('Create Static Page', ['create'], ['class' => 'btn btn-success']) ?>  </p>
    <?php } ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            [
                'attribute'=>'text',
                'value' => function($data){return crop_str(strip_tags($data->text),150,'...');},
              // 'value' => function($data){return strip_tags($data->text);},
              //  'format'=>'html',
            ],
            [
                'attribute'=>'language',
                'value' => function($data){return ($data->language==0)?'English':'Русский';},
                'filter' => array('' => 'All',0 => 'English', 1 => 'Русский'),
               ],
            ['class' => 'yii\grid\ActionColumn',
             'template' => $actionTemplate,],
        ],
    ]); ?>
</div>
