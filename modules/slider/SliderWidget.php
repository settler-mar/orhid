<?php

namespace app\modules\slider;

use app\modules\slider\models\SliderImages;
use yii\base\Widget;
use yii\helpers\Url;
use Yii;

class SliderWidget extends Widget
{
    public function run()
    {
        $AllImages = SliderImages::find()->all();
        if ($AllImages){
            return $this->render('sliderwidget_view', [
                'allimages' => $AllImages,
            ]);
        }
    }
}