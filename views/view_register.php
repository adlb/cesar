<form class="form-horizontal" method="POST" action="<?php echo url(array('controller' => 'user', 'action' => 'register'))?>" enctype="x-www-form-urlencoded">
<fieldset>

<!-- Form Name -->
<legend><?php t(':REGISTER_ACCOUNT')?></legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="email"><?php t(':EMAIL')?></label>
  <div class="col-md-3">
  <input id="email" name="email" placeholder="<?php t(':PLACEHOLDER_EMAIL')?>" class="form-control input-md" type="text" value="<?php disp($obj['form'], 'email') ?>">
  </div>
</div>
<!-- Password input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="password1"><?php t(':PASSWORD1')?></label>
  <div class="col-md-3">
    <input id="password1" name="password1" placeholder="<?php t(':PLACEHOLDER_PASSWORD1')?>" class="form-control input-md" type="password" autocomplete="off">
  </div>
</div>

<!-- Password input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="password2"><?php t(':PASSWORD2')?></label>
  <div class="col-md-3">
    <input id="password2" name="password2" placeholder="<?php t(':PLACEHOLDER_PASSWORD2')?>" class="form-control input-md" type="password" autocomplete="off">
  </div>
</div>

<!-- Button (Double) -->
<div class="form-group">
  <div class="col-md-2 pull-right">
    <a class="btn btn-default" href="<?php echo url(array('controller' => 'site', 'view' => 'home'))?>">Cancel</a>
    <button id="button2id" name="button2id" class="btn btn-primary">Validate</button>
  </div>
</div>

</fieldset>
</form>
