<ul>
<li><a href="<?php echo url(array('controller' => 'builder', 'action' => 'reCreateTables')) ?>"><?php t(':RECREATE_TABLES')?></a></li>
<li><a href="<?php echo url(array('controller' => 'builder', 'action' => 'deleteConfig')) ?>"><?php t(':DELETE_CONFIG')?></a></li>
</ul>          

<form class="form-horizontal" method="POST" action="<?php echo url(array('controller' => 'builder', 'action' => 'saveConfig'))?>">
<fieldset>

<!-- Form Name -->
<legend>Configuration Site</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="Title">Title</label>
  <div class="col-md-5">
  <input id="Title" name="Title" placeholder="user" class="form-control input-md" type="text" value="<?php disp($obj['config'], 'Title'); ?>" />
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="Contact">Contact Email</label>
  <div class="col-md-5">
  <input id="Contact" name="Contact" placeholder="email" class="form-control input-md" type="text" value="<?php echo $obj['config']['Contact']; ?>" />
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="TemplateName">Template Name</label>
  <div class="col-md-5">
  <input id="TemplateName" name="TemplateName" placeholder="template" class="form-control input-md" type="text" value="<?php echo $obj['config']['TemplateName']; ?>" />
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="Languages">Languages</label>
  <div class="col-md-5">
  <input id="Languages" name="Languages" placeholder="languages" class="form-control input-md" type="text" value="<?php disp($obj['config'], 'Languages'); ?>">
  <span class="help-block">Languages separated with ";"</span>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="ActiveLanguages">ActiveLanguages</label>
  <div class="col-md-5">
  <input id="ActiveLanguages" name="ActiveLanguages" placeholder="active languages" class="form-control input-md" type="text" value="<?php disp($obj['config'], 'ActiveLanguages'); ?>">
  <span class="help-block">Languages separated with ";"</span>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="SecretLine">SecretLine</label>
  <div class="col-md-5">
  <input id="SecretLine" name="SecretLine" placeholder="long chain to be kept secret" class="form-control input-md" type="text" value="<?php echo $obj['config']['SecretLine']; ?>">
  <span class="help-block">Long chain to be kept secret</span>
  </div>
</div>

<!-- Textarea -->
<div class="form-group">
  <label class="col-md-4 control-label" for="Analytics">Analytics</label>
  <div class="col-md-4">
    <textarea class="form-control" id="Analytics" name="Analytics"><?php echo $obj['config']['Analytics']; ?></textarea>
  </div>
</div>

<legend>Configuration Base de données</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="DBConnectionString">ConnectionString</label>
  <div class="col-md-8">
  <input id="DBConnectionString" name="DBConnectionString" placeholder="ConnectionString" class="form-control input-md" type="text" value="<?php disp($obj['config'], 'DBConnectionString'); ?>">
  <span class="help-block">Something like "mysql:host=localhost;dbname=test"</span>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="DBUser">DB User</label>
  <div class="col-md-5">
  <input id="DBUser" name="DBUser" placeholder="user" class="form-control input-md" type="text" value="<?php disp($obj['config'], 'DBUser'); ?>" autocomplete="off">
  </div>
</div>

<!-- Password input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="DBPassword">DB Password</label>
  <div class="col-md-5">
    <input id="DBPassword" name="DBPassword" placeholder="password" class="form-control input-md" type="text" autocomplete="off" value="<?php disp($obj['config'], 'DBPassword'); ?>">

  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="DBPrefix">DB Prefix</label>
  <div class="col-md-6">
  <input id="DBPrefix" name="DBPrefix" placeholder="prefix" class="form-control input-md" type="text" value="<?php disp($obj['config'], 'DBPrefix'); ?>">
  <span class="help-block">This text will prefix all table names</span>
  </div>
</div>

<legend>Configuration Mail</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="SMTPHosts">SMTP Hosts</label>
  <div class="col-md-6">
  <input id="SMTPHosts" name="SMTPHosts" placeholder="host;secondary_host" class="form-control input-md" type="text" value="<?php echo $obj['config']['SMTPHosts']; ?>">
  <span class="help-block">Host and secondary host separated with ';'</span>
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="SMTPAuth">Authenticate</label>
  <div class="col-md-4">
  <div class="checkbox">
    <label for="SMTPAuth">
      <input name="SMTPAuth" id="SMTPAuth" value="true" type="checkbox" <?php echo $obj['config']['SMTPAuth'] ? 'CHECKED' : ''; ?>>
      Active authentification
    </label>
    </div>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="SMTPUser">SMTP User</label>
  <div class="col-md-6">
  <input id="SMTPUser" name="SMTPUser" placeholder="user" class="form-control input-md" type="text" value="<?php echo $obj['config']['SMTPUser']; ?>">
  <span class="help-block">User name for smtp hosts</span>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="SMTPPassword">SMTP Password</label>
  <div class="col-md-6">
  <input id="SMTPPassword" name="SMTPPassword" placeholder="password" class="form-control input-md" type="text" value="<?php echo $obj['config']['SMTPPassword']; ?>">
  <span class="help-block">Password for smtp hosts</span>
  </div>
</div>


<legend>Configuration PayPal</legend>

<!-- Multiple Checkboxes -->
<div class="form-group">
  <label class="col-md-4 control-label" for="PaypalButtonId">Hosted Button Id</label>
  <div class="col-md-4">
  <div class="checkbox">
    <label for="PaypalButtonId">
      <input name="PaypalButtonId" id="PaypalButtonId" type="text" value="<?php echo $obj['config']['PaypalButtonId']; ?>">
      <span class="help-block">Get this id on PayPal web site</span>
    </label>
    </div>
  </div>
</div>


<legend>Configuration Page de maintenance</legend>

<!-- Multiple Checkboxes -->
<div class="form-group">
  <label class="col-md-4 control-label" for="Maintenance">Maintenance Page</label>
  <div class="col-md-4">
  <div class="checkbox">
    <label for="Maintenance">
      <input name="Maintenance" id="Maintenance" value="true" type="checkbox" <?php echo $obj['config']['Maintenance'] ? 'CHECKED' : ''; ?>>
      Activate
    </label>
    </div>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="MaintenanceRedirection">Redirection</label>
  <div class="col-md-6">
  <input id="MaintenanceRedirection" name="MaintenanceRedirection" placeholder="http://defaultpage" class="form-control input-md" type="text" value="<?php echo $obj['config']['MaintenanceRedirection']; ?>">
  <span class="help-block">To be used if maintenance is a redirection or leave blank and fill the corresponding acticle</span>
  </div>
</div>

<!-- Button (Double) -->
<div class="form-group">
  <label class="col-md-4 control-label" for="cancel"></label>
  <div class="col-md-8">
    <button id="cancel" name="todo" class="btn btn-default" value="cancel">Cancel</button>
    <button id="update" name="todo" class="btn btn-primary" value="update">Update</button>
  </div>
</div>

</fieldset>
</form>