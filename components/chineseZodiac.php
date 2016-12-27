<?php
namespace app\components;

use Yii;
use yii\base\Widget;

class ChineseZodiac extends Widget{
    protected $chineseZodiacArray = array(
        array('name' => 'monkey', 'unicode' => '猴'),
        array('name' => 'rooster', 'unicode' => '雞'),
        array('name' => 'dog', 'unicode' => '狗'),
        array('name' => 'pig', 'unicode' => '豬'),
        array('name' => 'rat', 'unicode' => '鼠'),
        array('name' => 'ox', 'unicode' => '牛'),
        array('name' => 'tiger', 'unicode' => '兎'),
        array('name' => 'rabbit', 'unicode' => '兔'),
        array('name' => 'dragon', 'unicode' => '龍'),
        array('name' => 'serpent', 'unicode' => '蛇'),
        array('name' => 'horse', 'unicode' => '馬'),
        array('name' => 'goat', 'unicode' => '羊'),
    );

    public $date;

    public function run(){
        return $this->chineseZodiacArray[date('Y',$this->date) % 12]['name'];
    }

}