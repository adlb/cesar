<div class="page-container">
    <div class="container" ng-controller="menuConfigCtrl" ng-init="fnm=<?php echo $obj['IsForceNonMaintenance']?>">
        <form class="form-horizontal" method="POST" action="<?php echo url(array('controller' => 'builder', 'action' => 'saveConfig'))?>">
            <div class="row">
                <fieldset>

                    <!-- Form Name -->
                    <legend><?php t(':GLOBAL_SETUP') ?></legend>

                    <!-- Text input-->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="Title"><?php t(':TITLE') ?></label>
                      <div class="col-md-5">
                      <input id="Title" name="Title" placeholder="Title" class="form-control input-md" type="text" value="<?php disp($obj['config'], 'Title'); ?>" />
                      </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="Contact"><?php t(':EMAIL_CONTACT') ?></label>
                      <div class="col-md-5">
                      <input id="Contact" name="Contact" placeholder="Email" class="form-control input-md" type="text" value="<?php echo $obj['config']['Contact']; ?>" />
                      </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="TemplateName"><?php t(':TEMPLATE_NAME') ?></label>
                      <div class="col-md-5">
                      <input id="TemplateName" name="TemplateName" placeholder="template" class="form-control input-md" type="text" value="<?php echo $obj['config']['TemplateName']; ?>" />
                      </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="Languages"><?php t(':LANGUAGES') ?></label>
                      <div class="col-md-5">
                      <input id="Languages" name="Languages" placeholder="languages" class="form-control input-md" type="text" value="<?php disp($obj['config'], 'Languages'); ?>">
                      <span class="help-block"><?php t(':LANGUAGES_HELP') ?></span>
                      </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="ActiveLanguages"><?php t(':LANGUAGES_ACTIVE') ?></label>
                      <div class="col-md-5">
                      <input id="ActiveLanguages" name="ActiveLanguages" placeholder="active languages" class="form-control input-md" type="text" value="<?php disp($obj['config'], 'ActiveLanguages'); ?>">
                      <span class="help-block"><?php t(':LANGUAGES_HELP') ?></span>
                      </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="SecretLine"><?php t(':SECRET_LINE') ?></label>
                      <div class="col-md-5">
                      <input id="SecretLine" name="SecretLine" placeholder="long chain to be kept secret" class="form-control input-md" type="text" value="<?php echo $obj['config']['SecretLine']; ?>">
                      <span class="help-block"><?php t(':SECRET_LINE_HELP') ?></span>
                      </div>
                    </div>

                    <!-- Textarea -->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="Analytics"><?php t(':ANALYTICS') ?></label>
                      <div class="col-md-4">
                        <textarea class="form-control" id="Analytics" name="Analytics"><?php echo $obj['config']['Analytics']; ?></textarea>
                      </div>
                    </div>

                    <legend><?php t(':GLOBAL_SETUP_DATABASE') ?></legend>

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

                    <legend>Configuration Page de maintenance</legend>

                    <!-- Multiple Checkboxes -->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="Maintenance">Maintenance Page</label>
                      <div class="col-md-4">
                      <div class="checkbox">
                        <label for="Maintenance">
                          <input name="Maintenance" id="Maintenance" value="true" type="checkbox" <?php echo $obj['config']['Maintenance'] ? 'CHECKED' : ''; ?>>
                          <?php t(':ACTIVATE') ?>
                        </label>
                        <span class="help-block"><a href="<?php echo url(array('fm' => true))?>"><?php t(':MAINTENANCEPAGE_LINK') ?></a></span>
                        </div>
                      </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="MaintenanceRedirection">Redirection</label>
                      <div class="col-md-6">
                      <input id="MaintenanceRedirection" name="MaintenanceRedirection" placeholder="http://defaultpage" class="form-control input-md" type="text" value="<?php echo $obj['config']['MaintenanceRedirection']; ?>">
                      <span class="help-block"><?php t(':MAINTENANCEPAGE_REDIRECTION_HELP') ?></span>
                      </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="MaintenanceRedirection">Force non-maintenance</label>
                      <div class="col-md-6">
                        <div ng-show="fnm">
                            <p>
                                <?php t(':MAINTENANCEPAGE_FORCE_ACTIVE') ?>
                                <a class="btn btn-default" href ng-click="removeFnm()"><?php t(':UNACTIVATE') ?></a>
                            </p>
                        </div>
                        <div ng-hide="fnm">
                            <p>
                                <?php t(':MAINTENANCEPAGE_FORCE_INACTIVE') ?>
                                <a class="btn btn-default" href ng-click="activeFnm()"><?php t(':ACTIVATE') ?></a>
                            </p>
                        </div>
                        <span class="help-block"><?php t(':MAINTENANCEPAGE_FORCE_HELP') ?></span>
                      </div>
                    </div>

                    <!-- Button (Double) -->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="cancel"></label>
                      <div class="col-md-8">
                        <button id="cancel" name="todo" class="btn btn-default" value="cancel"><?php t(':CANCEL') ?></button>
                        <button id="update" name="todo" class="btn btn-primary" value="update"><?php t(':SAVE') ?></button>
                      </div>
                    </div>
                </fieldset>
            </div>
        </form>
    </div>
</div>