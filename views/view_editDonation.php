<form class="form-horizontal" method="POST" action="<?php echo url(array('controller' => 'donation', 'action' => 'saveDonation'))?>" enctype="x-www-form-urlencoded">
<fieldset>

<!-- Form Name -->
<legend><?php t(':ACCOUNT_INFORMATION')?></legend>

<input type="hidden" name="id" id="id" value="<?php disp($obj['form'], 'id')?>" />
<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="userid">userId</label>
  <div class="col-md-5">
  <input id="userid" name="userid" placeholder="placeholder" class="form-control input-md" type="text" value="<?php disp($obj['form'], 'userid') ?>">
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="amount">Amount (euro)</label>
  <div class="col-md-4">
  <input id="amount" name="amount" placeholder="" class="form-control input-md" type="text" value="<?php disp($obj['form'], 'amount') ?>">
  </div>
</div>

<!-- Select Basic -->
<div class="form-group">
  <label class="col-md-4 control-label" for="type">Type</label>
  <div class="col-md-6">
    <select id="type" name="type" class="form-control">
      <option value="cb"  <?php echo $obj['form']['type'] == 'cb' ? 'SELECTED' : '' ?>><?php t(':TYPE_CB_PAYPAL') ?></option>
      <option value="vir" <?php echo $obj['form']['type'] == 'vir' ? 'SELECTED' : '' ?>><?php t(':TYPE_VIREMENT') ?></option>
      <option value="chq" <?php echo $obj['form']['type'] == 'chq' ? 'SELECTED' : '' ?>><?php t(':TYPE_CHEQUE') ?></option>
    </select>
  </div>
</div>

<!-- Select Basic -->
<div class="form-group">
  <label class="col-md-4 control-label" for="status">Status</label>
  <div class="col-md-6">
    <select id="status" name="status" class="form-control">
      <option value="promess"  <?php echo $obj['form']['status'] == 'cb' ? 'SELECTED' : '' ?>><?php t(':STATUS_PROMESS') ?></option>
      <option value="cancelled" <?php echo $obj['form']['status'] == 'vir' ? 'SELECTED' : '' ?>><?php t(':STATUS_CANCELLED') ?></option>
      <option value="received" <?php echo $obj['form']['status'] == 'chq' ? 'SELECTED' : '' ?>><?php t(':STATUS_RECEIVED') ?></option>
      <option value="validated" <?php echo $obj['form']['status'] == 'chq' ? 'SELECTED' : '' ?>><?php t(':STATUS_VALIDATED') ?></option>
      <option value="archived" <?php echo $obj['form']['status'] == 'chq' ? 'SELECTED' : '' ?>><?php t(':STATUS_ARCHIVED') ?></option>
      <option value="deleted" <?php echo $obj['form']['status'] == 'chq' ? 'SELECTED' : '' ?>><?php t(':STATUS_DELETED') ?></option>
    </select>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="externalCheckId"><?php t(':externalCheckId')?></label>
  <div class="col-md-6">
  <input id="externalCheckId" name="externalCheckId" placeholder="external id" class="form-control input-md" type="text" value="<?php disp($obj['form'], 'externalCheckId') ?>">
  <span class="help-block">n° de cheque ou n° de validation paypal</span>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="email">Email</label>
  <div class="col-md-4">
  <input id="email" name="email" placeholder="" class="form-control input-md" type="text" value="<?php disp($obj['form'], 'email') ?>">
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="firstName">Prénom</label>
  <div class="col-md-4">
  <input id="firstName" name="firstName" placeholder="" class="form-control input-md" type="text" value="<?php disp($obj['form'], 'firstName') ?>">
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="lastName">Nom</label>
  <div class="col-md-4">
  <input id="lastName" name="lastName" placeholder="placeholder" class="form-control input-md" type="text" value="<?php disp($obj['form'], 'lastName') ?>">
  </div>
</div>

<!-- Form Name -->
<legend><?php t(':ADDRESS')?></legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="addressLine1"><?php t(':ADDRESS_LINE_1')?></label>
  <div class="col-md-6">
  <input id="addressLine1" name="addressLine1" placeholder="placeholder" class="form-control input-md" type="text" value="<?php disp($obj['form'], 'addressLine1') ?>">
  <span class="help-block">help</span>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="addressLine2"><?php t(':ADDRESS_LINE_2')?></label>
  <div class="col-md-6">
  <input id="addressLine2" name="addressLine2" placeholder="placeholder" class="form-control input-md" type="text" value="<?php disp($obj['form'], 'addressLine2') ?>">
  <span class="help-block">help</span>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="postalCode"><?php t(':POSTAL_CODE')?></label>
  <div class="col-md-6">
  <input id="postalCode" name="postalCode" placeholder="placeholder" class="form-control input-md" type="text" value="<?php disp($obj['form'], 'postalCode') ?>">
  <span class="help-block">help</span>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="city"><?php t(':CITY')?></label>
  <div class="col-md-6">
  <input id="city" name="city" placeholder="placeholder" class="form-control input-md" type="text" value="<?php disp($obj['form'], 'city') ?>">
  <span class="help-block">help</span>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="country"><?php t(':country')?></label>
  <div class="col-md-6">
  <input id="country" name="country" placeholder="placeholder" class="form-control input-md" type="text" value="<?php disp($obj['form'], 'country') ?>">
  <span class="help-block">help</span>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="phone"><?php t(':phone')?></label>
  <div class="col-md-6">
  <input id="phone" name="phone" placeholder="placeholder" class="form-control input-md" type="text" value="<?php disp($obj['form'], 'phone') ?>">
  <span class="help-block">help</span>
  </div>
</div>

<!-- Button (Double) -->
<div class="form-group">
  <div class="col-md-2 pull-right">
    <a class="btn btn-default" href="<?php echo url(array('controller' => 'donation', 'view' => 'donationList')) ?>">Cancel</a>
    <button id="button2id" name="button2id" class="btn btn-primary">Validate</button>
  </div>
</div>

</fieldset>
</form>
