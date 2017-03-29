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
  $this->params['hide_title']=true;

  $old=date('Y',time()-$user->profile['birthday'])-1970;
  $online = (time()-($user->last_online)<User::MAX_ONLINE_TIME);
?>

<div class="content">
  <?php $form = ActiveForm::begin([id=>"msg_form"]); ?>

  <?=yii\imperavi\Widget::widget([
    // You can either use it for model attribute
    'model' => $model,
    'attribute' => 'message',

    // Some options, see http://imperavi.com/redactor/docs/
    'options' => [
      'toolbar' => true,
      'buttons' => [
        'bold',
        'italic',
        'unorderedlist',
        'orderedlist'
      ],
      /*'formatting'=> ['p','blockquote'],
      'formattingAdd'=>[
        "red-p-add"=>[
          title=> 'Red Block Add',
		      args=> ['p', 'class','red']
	      ],
      ]*/
    ],
    'plugins'=> [
      'inlinestyle',
      'userimage',
      'submit',
    ]
  ]);;?>

  <?php ActiveForm::end(); ?>

  <div class="file_insert_model">
    <div class="close">X</div>
    <div class="title">Select photo</div>
    <div class="photo_list">

    </div>

    <div>
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
          'fileuploaddone' => 'function(e, data) {
              console.log(e); console.log(data);
          }',
          'fileuploadfail' => 'function(e, data) {
            console.log(e); console.log(data);
          }',
          'fileuploadprocess' => 'function(e, data) {
            console.log(e); console.log(data);
          }',
        ],
      ]);?>
    </div>
  </div>

  <div class="into">
    <!--<div class="title_1"><span>Chat</span></div>-->



    <div class="in_mes in_mes_new">
      <div class="in_mes_top">
        <div class="in_mes_img"><img src="img/man.jpg"></div>
        <div class="in_mes_tit">
          <div class="in_mes_name">Vladimir_2017</div>
          <div class="in_mes_data">25.03.2017</div>
          <div class="in_mes_us">
            <span class="glyphicon glyphicon-chevron-up"></span>
            <!--							Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from Contrary to popular belief, Lorem Ipsum is not simply random text.			-->
          </div>

        </div>
      </div>

      <div class="in_mes_cont" style="display:block;">
        <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from Contrary to popular belief, Lorem Ipsum is not simply random text.</p>
        <p><strong>Contrary to popular belief, Lorem Ipsum is not simply random text.</strong> It has roots in a piece of classical Latin literature from Contrary to popular belief, Lorem Ipsum is not simply random text. Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from Contrary to popular belief, Lorem Ipsum is not simply random text Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from Contrary to popular belief, Lorem Ipsum is not simply random text</p>
      </div>
    </div>

    <div class="in_mes">
      <div class="in_mes_top">
        <div class="in_mes_img"><img src="img/women.jpg"></div>
        <div class="in_mes_tit">
          <div class="in_mes_name">Irishka_1990</div>
          <div class="in_mes_data">25.03.2017</div>
          <div class="in_mes_us">
            Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from Contrary to popular belief, Lorem Ipsum is not simply random text.
          </div>
        </div>
      </div>

      <div class="in_mes_cont">
        <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from Contrary to popular belief, Lorem Ipsum is not simply random text.</p>
        <p><strong>Contrary to popular belief, Lorem Ipsum is not simply random text.</strong> It has roots in a piece of classical Latin literature from Contrary to popular belief, Lorem Ipsum is not simply random text. Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from Contrary to popular belief, Lorem Ipsum is not simply random text Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from Contrary to popular belief, Lorem Ipsum is not simply random text</p>
      </div>
    </div>

    <div class="in_mes">
      <div class="in_mes_top">
        <div class="in_mes_img"><img src="img/man.jpg"></div>
        <div class="in_mes_tit">
          <div class="in_mes_name">Vladimir_2017</div>
          <div class="in_mes_data">25.03.2017</div>
          <div class="in_mes_us">
            Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from Contrary to popular belief, Lorem Ipsum is not simply random text.
          </div>

        </div>
      </div>

      <div class="in_mes_cont">
        <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from Contrary to popular belief, Lorem Ipsum is not simply random text.</p>
        <p><strong>Contrary to popular belief, Lorem Ipsum is not simply random text.</strong> It has roots in a piece of classical Latin literature from Contrary to popular belief, Lorem Ipsum is not simply random text. Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from Contrary to popular belief, Lorem Ipsum is not simply random text Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from Contrary to popular belief, Lorem Ipsum is not simply random text</p>
      </div>
    </div>

    <div class="in_mes">
      <div class="in_mes_top">
        <div class="in_mes_img"><img src="img/women.jpg"></div>
        <div class="in_mes_tit">
          <div class="in_mes_name">Irishka_1990</div>
          <div class="in_mes_data">25.03.2017</div>
          <div class="in_mes_us">
            Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from Contrary to popular belief, Lorem Ipsum is not simply random text.
          </div>
        </div>
      </div>

      <div class="in_mes_cont">
        <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from Contrary to popular belief, Lorem Ipsum is not simply random text.</p>
        <p><strong>Contrary to popular belief, Lorem Ipsum is not simply random text.</strong> It has roots in a piece of classical Latin literature from Contrary to popular belief, Lorem Ipsum is not simply random text. Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from Contrary to popular belief, Lorem Ipsum is not simply random text Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from Contrary to popular belief, Lorem Ipsum is not simply random text</p>
      </div>
    </div>







    <div class="clear"></div>



  </div>
</div>
