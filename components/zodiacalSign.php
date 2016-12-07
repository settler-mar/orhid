<?php
namespace app\components;

use Yii;
use yii\base\Widget;

class ZodiacalSign extends Widget{
    protected $zodiacArray = array(
        array('name' => 'capricorn',
            'key' => 9, 'unicode' => '♑', 'start' => '11-23', 'end' => '01-20'
        ),
        array('name' => 'aquarius',
            'key' => 10, 'unicode' => '♒', 'start' => '01-21', 'end' => '02-19'
        ),
        array('name' => 'pisces',
            'key' => 11, 'unicode' => '♓', 'start' => '02-20', 'end' => '03-20'
        ),
        array('name' => 'aries',
            'key' => 0, 'unicode' => '♈', 'start' => '03-21', 'end' => '04-20'
        ),
        array('name' => 'taurus',
            'key' => 1, 'unicode' => '♉', 'start' => '04-21', 'end' => '05-21'
        ),
        array('name' => 'gemini',
            'key' => 2, 'unicode' => '♊', 'start' => '05-22', 'end' => '06-21'
        ),
        array('name' => 'cancer',
            'key' => 3, 'unicode' => '♋', 'start' => '06-22', 'end' => '07-22'
        ),
        array('name' => 'leo',
            'key' => 4, 'unicode' => '♌', 'start' => '07-23', 'end' => '08-23'
        ),
        array('name' => 'virgo',
            'key' => 5, 'unicode' => '♍', 'start' => '08-24', 'end' => '09-23'
        ),
        array('name' => 'libra',
            'key' => 6, 'unicode' => '♎', 'start' => '09-24', 'end' => '10-23'
        ),
        array('name' => 'scorpio',
            'key' => 7, 'unicode' => '♏', 'start' => '10-24', 'end' => '11-22'
        ),
        array('name' => 'sagittarius',
            'key' => 8, 'unicode' => ' ♐', 'start' => '11-23', 'end' => '12-21'
        )
    );

    public $date;

    public function run(){
        $month = date('n',$this->date)-1;
        $day = date('j',$this->date);
        $signsstart = array(1=>21, 2=>20, 3=>20, 4=>20, 5=>20, 6=>20, 7=>21, 8=>22, 9=>23, 10=>23, 11=>23, 12=>23);
        $index=$day < $signsstart[$month + 1] ? $month - 1 :$month % 12;
        return '<span class="zodiac_icon">'.$this->zodiacArray[$index]['unicode'].'</span>'.$this->zodiacArray[$index]['name'];
    }

}