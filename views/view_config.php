<form class="form-horizontal" method="POST" action="<?php echo url(array('controller' => 'builder', 'action' => 'saveConfig'))?>">
<fieldset>

<!-- Form Name -->
<legend>Configuration</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="Title">Title</label>  
  <div class="col-md-5">
  <input id="Title" name="Title" placeholder="user" class="form-control input-md" type="text" value="<?php disp($obj['config'], 'Title'); ?>" autocomplete="off">
  </div>
</div>

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
    <input id="DBPassword" name="DBPassword" placeholder="password" class="form-control input-md" type="password" autocomplete="off">
    
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

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="Languages">Languages</label>  
  <div class="col-md-5">
  <input id="Languages" name="Languages" placeholder="languages" class="form-control input-md" type="text" value="<?php disp($obj['config'], 'Languages'); ?>">
  <span class="help-block">Languages separated with ";"</span>  
  </div>
</div>

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

<!-- Textarea -->
<div class="form-group">
  <label class="col-md-4 control-label" for="MaintenanceMessage">Text Area</label>
  <div class="col-md-4">                     
    <textarea class="form-control" id="MaintenanceMessage" name="MaintenanceMessage"><?php disp($obj['config'], 'MaintenanceMessage'); ?></textarea>
  </div>
</div>

<!-- Button (Double) -->
<div class="form-group">
  <label class="col-md-4 control-label" for="cancel"></label>
  <div class="col-md-8">
    <button id="cancel" name="cancel" class="btn btn-default">Cancel</button>
    <button id="Update" name="Update" class="btn btn-primary">Update</button>
  </div>
</div>

</fieldset>
</form>