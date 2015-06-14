<?php
    displayPartial('site', 'fixedArticle', array('titleKey' => 'CheckAdressBeforeDonate'));
?>

<?php t(':name') ?> : <?php echo $obj['user']['firstName'] . ' ' . $obj['user']['lastName'] ?><br/>
<?php t(':address') ?> : <?php echo $obj['user']['addressLine1'] . ' - ' . $obj['user']['addressLine2'] . ' - ' . $obj['user']['postalCode'] . ' ' . $obj['user']['city'] . ' - ' . $obj['user']['country'] ?><br/>

<a class="btn btn-default" href="<?php echo url(array(
    'controller' => 'user', 
    'view' => 'editUser', 
    'id' => $obj['user']['id'], 
    'callback' => url(array(
            'controller' => 'donation', 
            'view' => 'donate'
            ))
    ))?>" role="button"><?php t(":MODIFY_USER_INFO") ?></a><br/><br/>



<form method="post" class="form-horizontal" action="<?php echo url(array('controller' => 'donation', 'action' => 'donate')) ?>" >
<input type="hidden" name="userid" value="<?php echo $obj['user']['id'] ?>" />
<input type="hidden" name="email" value="<?php echo $obj['user']['email'] ?>" />
<input type="hidden" name="firstName" value="<?php echo $obj['user']['firstName'] ?>" />
<input type="hidden" name="lastName" value="<?php echo $obj['user']['lastName'] ?>" />
<input type="hidden" name="addressLine1" value="<?php echo $obj['user']['addressLine1'] ?>" />
<input type="hidden" name="addressLine2" value="<?php echo $obj['user']['addressLine2'] ?>" />
<input type="hidden" name="city" value="<?php echo $obj['user']['city'] ?>" />
<input type="hidden" name="country" value="<?php echo $obj['user']['country'] ?>" />
<input type="hidden" name="postalCode" value="<?php echo $obj['user']['postalCode'] ?>" />
<input type="hidden" name="phone" value="<?php echo $obj['user']['phone'] ?>" />
    
<fieldset>

<!-- Form Name -->
<legend><?php t(':DONATION') ?></legend>

<!-- Prepended text-->
<div class="form-group">
  <label class="col-md-4 control-label" for="amount"><?php t(':AMOUNT') ?></label>
  <div class="col-md-5">
    <div class="input-group">
      <span class="input-group-addon">€</span>
      <input id="amount" name="amount" class="form-control" placeholder="montant" required="" type="text">
    </div>
    <p class="help-block"><?php t(':AMOUNT_HELP') ?></p>
  </div>
</div>

<!-- Multiple Radios -->
<div class="form-group">
  <label class="col-md-4 control-label" for="type"><?php t(':PAYMENT_TYPE') ?></label>
  <div class="col-md-4">
  <div class="radio">
    <label for="type-0">
      <input name="type" id="type-0" value="cb" checked="checked" type="radio">
      <?php t(':PAYMENT_TYPE_CB') ?>
    </label>
	</div>
  <div class="radio">
    <label for="type-1">
      <input name="type" id="type-1" value="chq" type="radio">
      <?php t(':PAYMENT_TYPE_CHQ') ?>
    </label>
	</div>
  <div class="radio">
    <label for="type-2">
      <input name="type" id="type-2" value="vir" type="radio">
      <?php t(':PAYMENT_TYPE_VIR') ?>
    </label>
	</div>
  </div>
</div>

<!-- Button (Double) -->
<div class="form-group">
  <label class="col-md-4 control-label" for="todo"></label>
  <div class="col-md-8">
    <button id="todo" name="todo" class="btn btn-primary">Validate</button>
    <a href="<?php echo url(array('controller' => 'site')) ?>" class="btn btn-default">Cancel</a>
  </div>
</div>

</fieldset>
</form>