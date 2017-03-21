<?php
use yii\helpers\Html;

$this->title = 'Checkout';

if(!$user){
  ?>
  <script type='text/javascript'>
    $(document).ready(function() {
      popup.open({
        message: 'It is necessary to make a choice of the recipient of the gift.',
        type: 'err',
      });
    });
    </script>
  <?php
}
?>

<form id="shop_order_select" method="post">

  <div class="gift_add">
    <span><img src="<?=$shop['picture'];?>"></span>
    <a class="send_gift" href="/user-gift/<?=($user?$user->id:'0');?>">Choose gift</a>
  </div>

  <div class="g_next"><img src="img/g_next.png"></div>

  <div class="girl_add">
    <img src="<?=($user?($user->photo?$user->photo:'/img/not-ava.jpg'):'/img/not-ava.jpg');?>">
    <input type="radio" id="select_1" name="select">
    <input type="radio" id="select_2" name="select">
    <label class="send_gift" data-select-like-a-boss="1" for="select_1">Choose girl</label>
    <div class="chouse_girl_form">
      <span>Enter the ID of the girl.</span>
      <input class="girl_id_chose" type="text" value="<?=($user?$user->id:'')?>">
      <a class="send_gift" href="/user-gift/<?=$shop['id'];?>/<?=($user?$user->id:0)?>" base_href="/user-gift/" dop_href="/<?=$shop['id'];?>">Choose</a>
      <label class="send_gift" data-select-like-a-boss="1" for="select_2">Return</label>
    </div>
    <?php
      if($user) {
        ?>
        <div class="add_girl_info">
          <p class="g_name"><?=$user->first_name.' '.date('Y',$user->profile['birthday'])?></p>
          <p class="g_id">ID <?=$user->id;?></p>
          <p class="g_old"><span><?=date('Y',time()-$model->profile['birthday'])-1970;;?></span>years old</p>
          <p class="g_town"><?=$user->country_->name;?>/<?=$user->city_->city;?></p>
        </div>
        <?php
      }
    ?>

  </div>

  <div class="add_gift_tit"><?=$shop['title'];?></div>
  <div class="add_gift_price">$ <?=number_format($shop['price'],2,'.',' ');?></div>
  <div class="add_gift_txt">
    <p><?=$shop['description'];?></p>
  </div>

  <div class="add_gift_com">Comments</div>
  <textarea class="add_gift_com_txt" name="comments"><?=$request['comments'];?></textarea>
  <input type="submit" class="send_gift s_gift" value="Send gift">

</form>
