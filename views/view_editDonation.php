<section>
    <div class="page-content">
        <div class="container">
            <form class="form-horizontal" method="POST" action="<?php echo url(array('controller' => 'donation', 'action' => 'saveDonation'))?>" enctype="x-www-form-urlencoded">
                <fieldset>
                    <div class="row">
                        <legend class="col-md-12"><?php t(':DONATION')?></legend>
                        <input type="hidden" name="id" id="id" value="<?php disp($obj['form'], 'id')?>" />
                        <input type="hidden" name="callback" id="callback" value="<?php disp($obj, 'callback')?>" />
                        
                        <div class="row">
                            <!-- UserId input-->
                            <div class="form-group-sm">
                              <label class="col-md-4 control-label" for="userid">userId</label>
                              <div class="col-md-5">
                                <input id="userid" name="userid" class="form-control input-sm" type="text" value="<?php disp($obj['form'], 'userid') ?>">
                              </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Text input-->
                            <div class="form-group-sm">
                              <label class="col-md-4 control-label" for="amount"><?php t(':AMOUNT') ?> (euro)</label>
                              <div class="col-md-4">
                                <input id="amount" name="amount" class="form-control input-sm" type="text" value="<?php disp($obj['form'], 'amount') ?>">
                              </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Select Basic -->
                            <div class="form-group-sm">
                              <label class="col-md-4 control-label" for="type"><?php t(':TYPE_PAYMENT') ?></label>
                              <div class="col-md-6">
                                <select id="type" name="type" class="form-control">
                                  <option value="cb"  <?php echo $obj['form']['type'] == 'cb' ? 'SELECTED' : '' ?>><?php t(':TYPE_PAYMENT_cb') ?></option>
                                  <option value="vir" <?php echo $obj['form']['type'] == 'vir' ? 'SELECTED' : '' ?>><?php t(':TYPE_PAYMENT_vir') ?></option>
                                  <option value="chq" <?php echo $obj['form']['type'] == 'chq' ? 'SELECTED' : '' ?>><?php t(':TYPE_PAYMENT_chq') ?></option>
                                </select>
                              </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Select Basic -->
                            <div class="form-group-sm">
                              <label class="col-md-4 control-label" for="status"><?php t(':STATUS') ?></label>
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
                        </div>
                        
                        <div class="row">
                            <!-- Text input-->
                            <div class="form-group-sm">
                              <label class="col-md-4 control-label" for="externalCheckId"><?php t(':EXTERNAL_CHECK_ID')?></label>
                              <div class="col-md-6">
                              <input id="externalCheckId" name="externalCheckId" placeholder="<?php t(':PLACEHOLDER_EXTERNAL_CHECK_ID')?>" class="form-control input-sm" type="text" value="<?php disp($obj['form'], 'externalCheckId') ?>">
                              <span class="help-block"><?php t(':HELP_EXTERNAL_CHECK_ID')?></span>
                              </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Text input-->
                            <div class="form-group-sm">
                              <label class="col-md-4 control-label" for="email"><?php t(':EMAIL') ?></label>
                              <div class="col-md-4">
                              <input id="email" name="email" placeholder="" class="form-control input-sm" type="text" value="<?php disp($obj['form'], 'email') ?>">
                              </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Text input-->
                            <div class="form-group-sm">
                              <label class="col-md-4 control-label" for="firstName"><?php t(':FIRSTNAME') ?></label>
                              <div class="col-md-4">
                              <input id="firstName" name="firstName" placeholder="" class="form-control input-sm" type="text" value="<?php disp($obj['form'], 'firstName') ?>">
                              </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Text input-->
                            <div class="form-group-sm">
                              <label class="col-md-4 control-label" for="lastName"><?php t(':LASTNAME') ?></label>
                              <div class="col-md-4">
                              <input id="lastName" name="lastName" placeholder="<?php t(':PLACEHOLDER_LASTNAME') ?>" class="form-control input-sm" type="text" value="<?php disp($obj['form'], 'lastName') ?>">
                              </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Form Name -->
                            <legend class="col-md-12"><?php t(':ADDRESS')?></legend>
                        </div>
                        
                        <div class="row">
                            <!-- Text input-->
                            <div class="form-group-sm">
                              <label class="col-md-4 control-label" for="addressLine1"><?php t(':ADDRESS_LINE_1')?></label>
                              <div class="col-md-6">
                              <input id="addressLine1" name="addressLine1" placeholder="<?php t(':PLACEHOLDER_ADDRESS1') ?>" class="form-control input-sm" type="text" value="<?php disp($obj['form'], 'addressLine1') ?>">
                              </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Text input-->
                            <div class="form-group-sm">
                              <label class="col-md-4 control-label" for="addressLine2"><?php t(':ADDRESS_LINE_2')?></label>
                              <div class="col-md-6">
                              <input id="addressLine2" name="addressLine2" placeholder="<?php t(':PLACEHOLDER_ADDRESS2') ?>" class="form-control input-sm" type="text" value="<?php disp($obj['form'], 'addressLine2') ?>">
                              </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Text input-->
                            <div class="form-group-sm">
                              <label class="col-md-4 control-label" for="postalCode"><?php t(':POSTAL_CODE')?></label>
                              <div class="col-md-6">
                              <input id="postalCode" name="postalCode" placeholder="<?php t(':PLACEHOLDER_POSTALCODE') ?>" class="form-control input-sm" type="text" value="<?php disp($obj['form'], 'postalCode') ?>">
                              </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Text input-->
                            <div class="form-group-sm">
                              <label class="col-md-4 control-label" for="city"><?php t(':CITY')?></label>
                              <div class="col-md-6">
                              <input id="city" name="city" placeholder="<?php t(':PLACEHOLDER_CITY') ?>" class="form-control input-sm" type="text" value="<?php disp($obj['form'], 'city') ?>">
                              </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Text input-->
                            <div class="form-group-sm">
                              <label class="col-md-4 control-label" for="country"><?php t(':COUNTRY')?></label>
                              <div class="col-md-6">
                              <input id="country" name="country" placeholder="<?php t(':PLACEHOLDER_COUNTRY') ?>" class="form-control input-sm" type="text" value="<?php disp($obj['form'], 'country') ?>">
                              </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Text input-->
                            <div class="form-group-sm">
                              <label class="col-md-4 control-label" for="phone"><?php t(':PHONE')?></label>
                              <div class="col-md-6">
                              <input id="phone" name="phone" placeholder="<?php t(':PLACEHOLDER_PHONE') ?>" class="form-control input-sm" type="text" value="<?php disp($obj['form'], 'phone') ?>">
                              </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Button (Double) -->
                            <div class="form-group-sm">
                                <div class="col-md-12">
                                    <ul class="list-inline pull-right">
                                        <li>
                                            <a class="btn btn-default" href="<?php echo $obj['callback'] ?>"><?php t(':CANCEL') ?></a>
                                        </li>
                                        <li>
                                            <button id="button2id" name="button2id" class="btn btn-primary"><?php t(':SAVE') ?></button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>