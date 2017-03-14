<?php
  use yii\helpers\Html;
  use yii\grid\GridView;
  use app\modules\user\models\User;
  use app\modules\user\models\Profile;
  use app\components\timePassed;
  use app\components\emojione\Input;
  use yii\widgets\ActiveForm;


  $this->title = 'Mail';
  $this->params['breadcrumbs'][] = $this->title;
  $this->params['hide_title']=true;

  $old=date('Y',time()-$user->profile['birthday'])-1970;
  $online = (time()-($user->last_online)<User::MAX_ONLINE_TIME);
?>


<?php $form = ActiveForm::begin(); ?>

  <?= $form->field($model, 'message')->widget(\mervick\emojionearea\Widget::className(),
    [
      'options'=>[
        'filtersPosition'=>"bottom",
        'recentEmojis'=>false
      ],
      'pluginOptions'=>[
        'useSprite'=> false,
        'shortnames'=> true,
        'autoHideFilters'=> true,
        'recentEmojis'=>false,
        'imageType'=> "png",
        'useInternalCDN'=> false,
        'saveEmojisAs'=> "image",
      ],
    ]); ?>


  <div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
  </div>

<?php ActiveForm::end(); ?>