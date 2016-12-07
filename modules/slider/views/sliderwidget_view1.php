<?php
if ($allimages!=null)
foreach ($allimages as $arr) {?>
    <div class="headerimg" id="headerimg<?=$arr->image_id?>">
        <img src = "<?= $arr->address ?>" alt="<?= $arr->text ?>" >
    </div>
<?php } ?>
<?php
