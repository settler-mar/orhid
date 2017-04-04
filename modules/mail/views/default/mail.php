<?php
  use yii\helpers\Html;
  use yii\grid\GridView;
  use app\modules\user\models\User;
  use app\modules\user\models\Profile;
  use app\components\timePassed;
  use app\components\emojione\Input;
  use yii\widgets\ActiveForm;
  use yii\web\JsExpression;
  use dosamigos\fileupload\FileUploadUI;
  use dosamigos\fileupload\FileUpload;

  $this->title = 'Mail';
  $this->params['breadcrumbs'][] = $this->title;
  //$this->params['hide_title']=true;

  $old=date('Y',time()-$user->profile['birthday'])-1970;
  $online = (time()-($user->last_online)<User::MAX_ONLINE_TIME);
?>

  <?php $form = ActiveForm::begin(['id'=>"msg_form"]); ?>

  <?=yii\imperavi\Widget::widget([
    // You can either use it for model attribute
    'model' => $model,
    'attribute' => 'message',
    'id'=>"message",

    // Some options, see http://imperavi.com/redactor/docs/
    'options' => [
      'toolbar' => true,
      'buttons' => [
        'bold',
        'italic',
        'unorderedlist',
        'orderedlist'
      ],
      'pasteLinks'=>false,
      'pasteLinkTarget'=>false,
      'pasteBlockTags'=>[],
      'pasteInlineTags'=>[],
      'pasteImages'=>false,
      'dragImageUpload'=>false,
      'clipboardImageUpload'=>false,
      'multipleImageUpload'=>false,
      'imageCaption'=>false,
      'imageResizable'=>true,
      'removeComments'=>false,

    ],
    'plugins'=> [
      'inlinestyle',
      'userimage',
      'submit',
    ]
  ]);;?>

  <?php ActiveForm::end(); ?>

  <div class="file_insert_model_bg">
    <div class="file_insert_model">
      <div class="close">X</div>
      <div class="title">Select photo</div>
      <div class="photo_list">

      </div>

      <div>
        <div>
          You can upload new file from your PC.
        </div>
        <div class="progress_bar">
          <span></span>
        </div>
        <?= FileUpload::widget([
          'model'=>$fileupload,
          'attribute' => 'image',
          'url' => ['/fileupload/default/upload'], // your url, this is just for demo purposes,
          'options' => ['accept' => 'image/*'],
          'clientOptions' => [
            'maxFileSize' => 2000
          ], // Also, you can specify jQuery-File-Upload events
        //// see: https://github.com/blueimp/jQuery-File-Upload/wiki/Options#processing-callback-options
          'clientEvents' => [
            'fileuploaddone' => 'fileuploaddone',
            'fileuploadfail' => 'fileuploadfail',
            'fileuploadprogress' => 'fileuploadprogress',
            'fileuploadstart' => 'fileuploadstart',
          ],
        ]);?>
      </div>
      <a class="insert_photo">Insert photo</a>
    </div>
  </div>

  <div class="into">
    <!--<div class="title_1"><span>Chat</span></div>-->



    <?php
    $dt=strtotime("today");
      foreach ($mails as $msg_num =>$mail){
        $do_css='';
        if($msg_num>0){
          $do_css.=' close_message';
        };

        if($m_id==$mail['user_from']){
          $u=Yii::$app->user->identity;
        }else{
          if($mail['is_read']==0){
            $do_css.=' in_mes_new';
          }
          $u=$user;
        }
        ?>
        <div class="in_mes <?=$do_css;?>">
          <div class="in_mes_top">
            <div class="in_mes_img"><img src="<?=$u->getPhoto();?>"></div>
            <div class="in_mes_tit">
              <div class="in_mes_name"><?=$u->getFullNick();?></div>
              <div class="in_mes_data"><?=date($dt>$mail['created_at']?"d.m.Y":'H:i',$mail['created_at'])?></div>
              <div class="in_mes_us">
                <span class="glyphicon glyphicon-chevron-up"></span>
                <span class="anotation">
                  <?=mb_substr(strip_tags($mail['message']),0,300);?>
                </span>
              </div>

            </div>
          </div>

          <div class="in_mes_cont">
            <?=$mail['message'];?>
          </div>
        </div>
        <?php
      };
    ?>

    <div class="clear"></div>

  </div>
