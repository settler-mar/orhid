<?php
use yii\bootstrap\ActiveForm;
?>

<?php $form = ActiveForm::begin() ?>
  <input type="hidden" value="1" name="method">
  <div class="credit-card-input" id="skeuocard">
    <p class="no-support-warning">
      Either you have Javascript disabled, or you're using an unsupported browser, amigo! That's why you're seeing this old-school credit card input form instead of a fancy new Skeuocard. On the other hand, at least you know it gracefully degrades...
    </p>
    <label for="cc_type">Card Type</label>
    <input name="cc_type" type="hidden" value="<?=$post['cc_type'];?>">
    <label for="cc_number">Card Number</label>
    <input type="text" name="cc_number" id="cc_number" placeholder="XXXX XXXX XXXX XXXX" maxlength="19" size="19" value="<?=$post['cc_number'];?>">
    <label for="cc_exp_month">Expiration Month</label>
    <input type="text" name="cc_exp_month" id="cc_exp_month" placeholder="00" value="<?=$post['cc_exp_month'];?>">
    <label for="cc_exp_year">Expiration Year</label>
    <input type="text" name="cc_exp_year" id="cc_exp_year" placeholder="00" value="<?=$post['cc_exp_year'];?>">
    <label for="cc_name">Cardholder's Name</label>
    <input type="text" name="cc_name" id="cc_name" placeholder="John Doe" value="<?=$post['cc_name'];?>">
    <label for="cc_cvc">Card Validation Code</label>
    <input type="text" name="cc_cvc" id="cc_cvc" placeholder="123" maxlength="3" size="3">
  </div>

<input type="submit">
<?php ActiveForm::end() ?>

<script>
  $(document).ready(function(){
    card = new Skeuocard($("#skeuocard"),{
      acceptedCardProducts: ['visa', 'mastercard'],
      genericPlaceholder: '**** **** **** ****',
      validationState: {
        number: true,
        exp: true,
        name: true,
        cvc: true
      },
      dontFocus: true
    });
    $('form').on("submit",function(){
      if( !card.isValid()){
        popup.open({message:'Check that the data card is required.',type:'err'});
        return false;
      };
    })
  });
</script>
