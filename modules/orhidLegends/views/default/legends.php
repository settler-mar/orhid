<?php
use yii\helpers\Html;

$this->title = 'Legends orhid';
?>

<?php  if ($canCreate) { ?>
  <p><?= Html::a('Create Orhid Legend', ['create'], ['class' => 'btn btn-success']) ?>  </p>
<?php } ?>

<div class="text_legend">
				<p>Еще один секрет счастья в семейной жизни – это внимание друг к другу. Муж и жена должны постоянно оказывать друг другу знаки самого нежного внимания и любви. Счастье жизни составляется из отдельных минут, из маленьких удовольствий – от поцелуя, улыбки, доброго взгляда, сердечного комплимента и бесчисленных маленьких, но добрых мыслей и искренних чувств. Любви тоже нужен ее ежедневный хлеб.</p>
				</div>

<div class="all_legend">

  <?php foreach ($legends as $legend){ ?>
    <div class="legend_block">
      <div class="image_legend">
        <img src=<?= $legend->cover ?> >
      </div>

      <div class="legend_icon">
        <span class="glyphicon glyphicon-camera"></span>
        <?php if ($legend->video) {?> <span class="glyphicon glyphicon-facetime-video"></span> <?php }?>
      </div>
      <div class="leg_tit">
        <?= $legend->title ?>
      </div>



    </div>

      <?php  if ($canUpdate) { ?>
        <?= Html::a('Update', ['update', 'id' => $legend->id], ['class' => 'btn btn-primary']) ?>
      <?php } ?>
      <?php  if ($canDelete) { ?>
        <?= Html::a('Delete', ['delete', 'id' => $legend->id], [
          'class' => 'btn btn-danger',
          'data' => [
            'confirm' => 'Are you sure you want to delete this item?',
            'method' => 'post',
          ],
        ]) ?>
      <?php } ?>

  <?php } ?>

				</div>



