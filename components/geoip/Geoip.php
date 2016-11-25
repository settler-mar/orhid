<?php

namespace app\components\geoip;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;


class Geoip extends Widget
{
    public $city_id;
    public $country_id;

    public function init() {
        $ip_city = \Yii::$app->session->get('ip_city');
        if ($ip_city === null){
            $geo = new \jisoft\sypexgeo\Sypexgeo();
            $geo->get('31.202.224.10');
            $country_id=$geo->country['id'];
            $city_id=$geo->city['id'];

            \Yii::$app->session['ip_city'] = $city_id;
            \Yii::$app->session['ip_country'] = $country_id;
        }else{
            $city_id    = \Yii::$app->session->get('ip_city');
            $country_id = \Yii::$app->session->get('ip_country');
        }
    }
}