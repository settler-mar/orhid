<?php
use yii\helpers\Html;

if($user) {
  $this->title = 'Shop items for ' . $user->first_name . ' ' . date('Y', $user->profile['birthday']);;
}else{
  $this->title = 'Shop items';
}
?>

<div class="shop_all">
  <?php
    foreach($shop as $item){
  ?>
    <div class="tovar">
      <a href="/user-gift/<?=$user_id;?>/<?=$item['id'];?>" class="pic">
        <img src="<?=$item['picture'];?>" alt="<?=$item['title'];?>">
        <div class="description_anim">
          <span>
            <?=$item['description'];?>
          </span>
        </div>
      </a>
      <p><?=$item['title'];?></p>
      <span class="tov_price">$ <?=number_format($item['price'],2,'.',' ');?></span>
      <a href="/user-gift/<?=$user_id;?>/<?=$item['id'];?>" class="add_cart">buy gift</a>
    </div>
  <?php
    };
  ?>
</div>
