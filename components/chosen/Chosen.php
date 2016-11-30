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
    public $selected=null;
    public $valueText;
    public $valueDopText;
    public $type='list';
    public $className='my_select_box';
    public $model=false;
    public $template="{input}\n{hint}\n{error}";

    public function init()
    {
        parent::init();
        //print_r($this->data);
    }

    public function run()
    {
        if($this->selected==null){
            $this->selected=$this->model[$this->name];
        }
        if(!$this->options['placeholder']){
            $this->options['placeholder']=Html::encode($this->model->getAttributeLabel($this->name));
        }
        $name=$this->model?Html::getInputName($this->model,$this->name):'$this->name';
        $out='<select class="'.$this->className.'" name="'.$name.'" data-placeholder="'.$this->options['placeholder'].'">';
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

        $this->template=str_replace('{input}',$out,$this->template);
        $this->template=str_replace('{hint}','',$this->template);
        $this->template=str_replace('{error}','',$this->template);
        $this->template=str_replace('{label}','<label class=control-label>'.$this->options['placeholder'].'</label>',$this->template);
        return $this->template;
    }
}
