<?php
namespace app\components\chosen;

use Yii;
use yii\base\Widget;
use yii\base\Component;
use yii\helpers\Html;


class Chosen extends Widget
{
    public $name;
    public $data;
    public $options;
    public $selected;
    public $valueText;
    public $valueDopText;
    public $type='list';
    public $className='my_select_box';

    public function init()
    {
        parent::init();
        //print_r($this->data);


    }

    public function run()
    {
        $out='<select class="'.$this->className.'" name="'.$this->name.'" data-placeholder="'.$this->options['placeholder'].'">';
        foreach ($this->data as $key => $row){
            $out.='<option ';

            if($this->type=='object') {
                if ($this->selected == $row['id'])
                    $out .= ' selected';
                if (isset($this->options['data-img-src'])) {
                    $out .= ' data-img-src="' . $this->options['src_prefix'] . $row[$this->options['data-img-src']] . '.png"';
                }
                $out .= '   value="' . $row['id'] . '">' . $row[$this->valueText];
                if ($this->valueDopText !== null) {
                    $out .= ' (' . $row[$this->valueDopText] . ')';
                }
            }else{
                if ($this->selected == $key)
                    $out .= ' selected';
                $out .= '   value="' . $key . '">' . $row;
            }
            $out.='</option>';
        }

        $out.='<select>';
        return $out;
    }
}
