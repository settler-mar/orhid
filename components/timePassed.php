<?php
namespace app\components;

use Yii;
use yii\base\Widget;

class TimePassed extends Widget{
    public $timeStart;

    public function run(){
        $a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
            30 * 24 * 60 * 60       =>  'month',
            24 * 60 * 60            =>  'day',
            60 * 60                 =>  'hour',
            60                      =>  'minute',
            1                       =>  'second'
        );

        if($this->timeStart==0)return'';
        $etime=time()-$this->timeStart;

        foreach ($a as $secs => $str)
        {
            $d = $etime / $secs;
            if ($d >= 1)
            {
                $r = round($d);
                return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
            }
        }
    }

}