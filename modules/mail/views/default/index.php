<?php
$this->title = 'Mail box';
$this->params['breadcrumbs'][] = $this->title;
//$this->params['hide_title']=true;

?>
<!--<div class="title_1"><span>Chat</span></div>-->
<?php
    if($mails_list) {
        foreach ($mails_list as $mail) {
            $user = $users[$mail['user_id']];
            $detail = $mails_detail[$mail['msg_id']];
            ?>
            <a class="mes_block <?= $mail['not_read'] ? 'mes_new' : ''; ?>" href="/mail/<?=$mail['user_id'];?>">
                <div class="mes_user">
                    <div class="mes_icon">
                        <?= $my_id == $detail->user_from ?
                          '<span class="glyphicon glyphicon-share-alt"></span>' : ''; ?>
                    </div>
                    <div class="mes_img">
                        <img src="<?= $user->getPhoto(); ?>">
                        <?= in_array($user->id, $favorites) ?
                          '<span class="glyphicon glyphicon-star"></span>' : ''; ?>
                    </div>
                    <div class="mes_name">
                        <p><?= $user->getFullNick(); ?></p>
                    </div>
                </div>
                <div class="mes_tit">
                    <div class="mes_count">
                        <?= $mail['cnt']; ?>
                    </div>
                    <div class="mes_txt">
                        <p>
                            <?= mb_substr(strip_tags($detail->message), 0, 300); ?>
                        </p>
                    </div>
                </div>
                <div class="mes_date">
                    <span>
                        <?= date(strtotime("today") > $detail->created_at ? "d.m.Y" : 'H:i', $detail->created_at) ?>
                    </span>
                </div>
            </a>
            <?php
        }
    }else{
        ?>
        <p>
            You do not have any letters yet. To start a conversation, select the person you are talking to.
        </p>
        <?php
    }
?>





<div class="clear"></div>
