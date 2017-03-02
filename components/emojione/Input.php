<?php

namespace app\components\emojione;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

class Input extends InputWidget
{
  /**
   * @var array the HTML attributes for the textarea input
   */
  public $options;

  /**
   * @var array Plugin options that will be passed to the emojioneArea
   */
  public $pluginOptions;


  public function init()
  {
    parent::init();

    $this->generateId();
    $this->registerAssets();
    echo $this->renderInput();
  }

  /**
   * Generate HTML identifiers for elements
   */
  protected function generateId()
  {
    if (empty($this->options['id'])) {
      $this->options['id'] = $this->getId();
    } else {
      $this->options['id'] = preg_replace('/[^a-z0-9_]/i', '_', $this->options['id']);
    }
  }

  /**
   * Register client assets
   */
  protected function registerAssets()
  {
    $view = $this->getView();
    Asset::register($view);

    $pluginOptions = !empty($this->pluginOptions) ? Json::encode($this->pluginOptions) : '';

    $js = <<<JS
    window.emojioneArea_{$this->options['id']} = jQuery("#{$this->options['id']}").emojioneArea($pluginOptions);
JS;
    Yii::$app->getView()->registerJs($js);
  }

  /**
   * Render the text area input
   */
  protected function renderInput()
  {
    if ($this->hasModel()) {
      //$input = Html::activeInput($this->model, $this->attribute, $this->options);
      //type, model, model attribute name, options
      $input = Html::activeInput('text', $this->model, 'name', $this->options);
    } else {
      //$input = Html::input($this->name, $this->value, $this->options);
      //type, input name, input value, options
      $input = Html::input('text', 'username', $this->name, $this->options);
    }
    return $input;
  }

}