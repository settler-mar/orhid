<?php
namespace app\components;

use Yii;
use yii\base\Widget;
use app\modules\user\models\Profile;

class PortfolioLine extends Widget{
    public $portfolio;
    public $params;
    public $not_show = 0;

    public function run(){
        if ($this->portfolio[$this->params]==$this->not_show) return '';
        if (strlen($this->portfolio[$this->params])==0) return '';

        $out = '<p><span>';
        $attributes=Profile::attributeLabels();
        $out.= $attributes[$this->params];
        $out.= '</span>';
        $out.= Profile::getSelectValue($this->params,$this->portfolio);
        return $out;
    }

}