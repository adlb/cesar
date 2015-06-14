<script language="javascript" src="js/md5.js"></script>
<script language="javascript">
<!--
  function doChallengeResponse() {
    pwnh1 = $("#passwordnothashed1").val();
    pwnh2 = $("#passwordnothashed2").val();
    if (pwnh1 == pwnh2)
    { 
        str1 = $("#email").val()+"*"+$("#passwordnothashed1").val();
        for(var i=0; i<1000; i++)
        {
            str1 = MD5(str1);
        }
        $("#password1").val(str1);
        $("#password2").val(str1);
    } else {
        $("#password1").val("mismatch");
        $("#password2").val("");
    }
    $("#passwordnothashed1").val("");
    $("#passwordnothashed2").val("");
  }
// -->
</script>
<form name="identification" class="form-horizontal" method="POST" action="<?php echo url(array('controller' => 'user', 'action' => 'register'))?>" enctype="x-www-form-urlencoded">
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
  <label class="col-md-4 control-label" for="passwordnothashed1"><?php t(':PASSWORD1')?></label>
  <div class="col-md-3">
    <input id="passwordnothashed1" name="passwordnothashed1" placeholder="<?php t(':PLACEHOLDER_PASSWORD1')?>" class="form-control input-md" type="password" autocomplete="off">
    <input id="password1" type="hidden" name="password1" />
  </div>
</div>

<!-- Password input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="passwordnothashed2"><?php t(':PASSWORD2')?></label>
  <div class="col-md-3">
    <input id="passwordnothashed2" name="passwordnothashed2" placeholder="<?php t(':PLACEHOLDER_PASSWORD2')?>" class="form-control input-md" type="password" autocomplete="off">
    <input id="password2" type="hidden" name="password2" />
  </div>
</div>

<!-- Button (Double) -->
<div class="form-group">
  <div class="col-md-2 pull-right">
    <a class="btn btn-default" href="<?php echo url(array('controller' => 'site', 'view' => 'home'))?>">Cancel</a>
    <input class="btn btn-primary" onClick="doChallengeResponse();" type="submit" name="submitbtn" value="Validate" />
  </div>
</div>

</fieldset>
</form>
