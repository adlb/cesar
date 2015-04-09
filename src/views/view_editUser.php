<form class="form-horizontal" method="POST" action="<?php echo url(array('controller' => 'user', 'action' => 'saveUser'))?>" enctype="x-www-form-urlencoded">
<fieldset>

<!-- Form Name -->
<legend><?php t(':ACCOUNT_INFORMATION')?></legend>

<input type="hidden" name="id" id="id" value="<?php disp($obj['form'], 'id')?>" />
<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="email">email</label>  
  <div class="col-md-5">
  <input id="email" name="email" placeholder="placeholder" class="form-control input-md" type="text" value="<?php disp($obj['form'], 'email') ?>">
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="firstName">Prenom</label>  
  <div class="col-md-4">
  <input id="firstName" name="firstName" placeholder="placeholder" class="form-control input-md" type="text" value="<?php disp($obj['form'], 'firstName') ?>">
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

<?php if ($obj['isAdministrator']) { ?>

<!-- Form Name -->
<legend><?php t(':ADMINISTRATION_DATA')?></legend>

<!-- Select Basic -->
<div class="form-group">
  <label class="col-md-4 control-label" for="role"><?php t(':ROLE')?></label>
  <div class="col-md-6">
    <select id="role" name="role" class="form-control">
      <?php foreach(array('Administrator', 'Translator', 'Visitor') as $v) { ?>
      <option value="<?php echo $v ?>" <?php echo $obj['form']['role'] == $v ? 'SELECTED' : ''; ?>><?php t(':ROLE_'.$v) ?></option>
      <?php } ?>
    </select>
  </div>
</div>

<!-- Select Basic -->
<div class="form-group">
  <label class="col-md-4 control-label" for="emailStatus"><?php t(':EMAIL_STATUS')?></label>
  <div class="col-md-6">
    <select id="emailStatus" name="emailStatus" class="form-control">
      <?php foreach(array('Valid', 'NotValidYet') as $v) { ?>
      <option value="<?php echo $v ?>" <?php echo $obj['form']['emailStatus'] == $v ? 'SELECTED' : ''; ?>><?php t(':EMAILSTATUS_'.$v) ?></option>
      <?php } ?>
    </select>
  </div>
</div>

<!-- Select Basic -->
<div class="form-group">
  <label class="col-md-4 control-label" for="origin"><?php t(':USER_ORIGIN')?></label>
  <div class="col-md-6">
    <select id="origin" name="origin" class="form-control">
      <?php foreach(array('Register', 'Payment', 'External') as $v) { ?>
      <option value="<?php echo $v ?>" <?php echo $obj['form']['origin'] == $v ? 'SELECTED' : ''; ?>><?php t(':EMAILSTATUS_'.$v) ?></option>
      <?php } ?>
    </select>
  </div>
</div>



<?php } ?>

<!-- Button (Double) -->
<div class="form-group">
  <div class="col-md-2 pull-right">
    <a class="btn btn-default" href="?controller=site">Cancel</a>
    <button id="button2id" name="button2id" class="btn btn-primary">Validate</button>
  </div>
</div>

</fieldset>
</form>
