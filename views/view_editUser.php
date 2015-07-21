    <div class="page-container">
        <div class="container" id="articleCtrl" ng-controller="articleCtrl">
            <form class="form-horizontal" method="POST" action="<?php echo url(array('controller' => 'user', 'action' => 'saveUser'))?>" enctype="x-www-form-urlencoded">
                <div class="row">
                    <div class="col-md-12">
                    <input type="hidden" name="callback" value="<?php disp($obj, 'callback') ?>">
                    <input type="hidden" name="email" value="<?php disp($obj['form'], 'email') ?>">
                    <fieldset>

                    <!-- Form Name -->
                    <legend><?php t(':ACCOUNT_INFORMATION')?> - <?php disp($obj['form'], 'email') ?></legend>

                    <input type="hidden" name="id" id="id" value="<?php disp($obj['form'], 'id')?>" />
                    
                    <!-- Text input-->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="lastName"><?php t(':LASTNAME') ?></label>
                      <div class="col-md-4">
                      <input id="lastName" name="lastName" placeholder="<?php t(':PLACEHOLDER_LASTNAME') ?>" class="form-control input-md" type="text" value="<?php disp($obj['form'], 'lastName') ?>">
                      </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="firstName"><?php t(':FIRSTNAME') ?></label>
                      <div class="col-md-4">
                      <input id="firstName" name="firstName" placeholder="<?php t(':PLACEHOLDER_FIRSTNAME') ?>" class="form-control input-md" type="text" value="<?php disp($obj['form'], 'firstName') ?>">
                      </div>
                    </div>

                    <!-- Form Name -->
                    <legend><?php t(':ADDRESS')?></legend>

                    <!-- Text input-->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="addressLine1"><?php t(':ADDRESS_LINE_1')?></label>
                      <div class="col-md-6">
                      <input id="addressLine1" name="addressLine1" placeholder="<?php t(':PLACEHOLDER_ADDRESS1') ?>" class="form-control input-md" type="text" value="<?php disp($obj['form'], 'addressLine1') ?>">
                      </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="addressLine2"><?php t(':ADDRESS_LINE_2')?></label>
                      <div class="col-md-6">
                      <input id="addressLine2" name="addressLine2" placeholder="<?php t(':PLACEHOLDER_ADDRESS2') ?>" class="form-control input-md" type="text" value="<?php disp($obj['form'], 'addressLine2') ?>">
                      </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="postalCode"><?php t(':POSTAL_CODE')?></label>
                      <div class="col-md-6">
                      <input id="postalCode" name="postalCode" placeholder="<?php t(':PLACEHOLDER_POSTALCODE') ?>" class="form-control input-md" type="text" value="<?php disp($obj['form'], 'postalCode') ?>">
                      </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="city"><?php t(':CITY')?></label>
                      <div class="col-md-6">
                      <input id="city" name="city" placeholder="<?php t(':PLACEHOLDER_CITY') ?>" class="form-control input-md" type="text" value="<?php disp($obj['form'], 'city') ?>">
                      </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="country"><?php t(':COUNTRY')?></label>
                      <div class="col-md-6">
                      <input id="country" name="country" placeholder="<?php t(':PLACEHOLDER_COUNTRY') ?>" class="form-control input-md" type="text" value="<?php disp($obj['form'], 'country') ?>">
                      </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="phone"><?php t(':PHONE')?></label>
                      <div class="col-md-6">
                      <input id="phone" name="phone" placeholder="<?php t(':PLACEHOLDER_PHONE') ?>" class="form-control input-md" type="text" value="<?php disp($obj['form'], 'phone') ?>">
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
                          <?php foreach(array('Administrator', 'Translator', 'Visitor', 'NewsLetter') as $v) { ?>
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
                          <?php foreach(array('Valid', 'NotValidYet', 'OptOut') as $v) { ?>
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
                          <?php foreach(array('Register', 'Payment', 'External', 'NewsLetter') as $v) { ?>
                          <option value="<?php echo $v ?>" <?php echo $obj['form']['origin'] == $v ? 'SELECTED' : ''; ?>><?php t(':USERORIGIN_'.$v) ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                    <?php } ?>

                    <!-- Button (Double) -->
                    <div class="form-group">
                      <div class="col-md-12 text-right">
                        <a class="btn btn-default" href="<?php disp($obj, 'callback') ?>"><?php t(':CANCEL') ?></a>
                        <button id="button2id" name="button2id" class="btn btn-primary"><?php t(':SAVE') ?></button>
                      </div>
                    </div>
                    </fieldset>
                    </div>
                </div>
            </form>
        </div>
    </div>