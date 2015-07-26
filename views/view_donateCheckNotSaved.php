<form method="post" class="form-horizontal" action="<?php echo url(array('controller' => 'donation', 'action' => 'saveDonationForm')) ?>" >
    <div class="row">
        <div class="col-md-12 text-right" style="margin-bottom:10px;">
        <?php if (!$obj['isLogged']) { ?>
            <a href="<?php echo url(array('controller' => 'user', 'view' => 'login', 'callback' =>
                url(array('controller' => 'donation', 'action' => 'donate', 'amount' => $obj['amount'], 'type' => $obj['type']))
            )) ?>" class="">
                <?php t(':I_HAVE_AN_ACCOUNT') ?>
            </a>
        <?php } ?>
        </div>
    </div>
        <!-- Text input-->
        <div class="form-group">
          <label class="col-md-3 control-label" for="lastName"><?php t(':LASTNAME') ?></label>
          <div class="col-md-9">
          <input id="lastName" name="lastName" placeholder="<?php t(':PLACEHOLDER_LASTNAME') ?>" class="form-control input-sm" type="text" value="<?php disp($obj, 'lastName') ?>">
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-3 control-label" for="firstName"><?php t(':FIRSTNAME') ?></label>
          <div class="col-md-9">
          <input id="firstName" name="firstName" placeholder="<?php t(':PLACEHOLDER_FIRSTNAME') ?>" class="form-control input-sm" type="text" value="<?php disp($obj, 'firstName') ?>">
          </div>
        </div>

        <!-- Text input-->
        <div class="form-group">
          <label class="col-md-3 control-label" for="addressLine1"><?php t(':ADDRESS_LINE_1')?></label>
          <div class="col-md-9">
          <input id="addressLine1" name="addressLine1" placeholder="<?php t(':PLACEHOLDER_ADDRESS1') ?>" class="form-control input-sm" type="text" value="<?php disp($obj, 'addressLine1') ?>">
          </div>
        </div>

        <!-- Text input-->
        <div class="form-group">
          <label class="col-md-3 control-label" for="addressLine2"><?php t(':ADDRESS_LINE_2')?></label>
          <div class="col-md-9">
          <input id="addressLine2" name="addressLine2" placeholder="<?php t(':PLACEHOLDER_ADDRESS2') ?>" class="form-control input-sm" type="text" value="<?php disp($obj, 'addressLine2') ?>">
          </div>
        </div>

        <!-- Text input-->
        <div class="form-group">
          <label class="col-md-3 control-label" for="postalCode"><?php t(':POSTAL_CODE')?></label>
          <div class="col-md-9">
          <input id="postalCode" name="postalCode" placeholder="<?php t(':PLACEHOLDER_POSTALCODE') ?>" class="form-control input-sm" type="text" value="<?php disp($obj, 'postalCode') ?>">
          </div>
        </div>

        <!-- Text input-->
        <div class="form-group">
          <label class="col-md-3 control-label" for="city"><?php t(':CITY')?></label>
          <div class="col-md-9">
          <input id="city" name="city" placeholder="<?php t(':PLACEHOLDER_CITY') ?>" class="form-control input-sm" type="text" value="<?php disp($obj, 'city') ?>">
          </div>
        </div>

        <!-- Text input-->
        <div class="form-group">
          <label class="col-md-3 control-label" for="country"><?php t(':COUNTRY')?></label>
          <div class="col-md-9">
          <input id="country" name="country" placeholder="<?php t(':PLACEHOLDER_COUNTRY') ?>" class="form-control input-sm" type="text" value="<?php disp($obj, 'country') ?>">
          </div>
        </div>

        <!-- Text input-->
        <div class="form-group">
          <label class="col-md-3 control-label" for="phone"><?php t(':PHONE')?></label>
          <div class="col-md-9">
          <input id="phone" name="phone" placeholder="<?php t(':PLACEHOLDER_PHONE') ?>" class="form-control input-sm" type="text" value="<?php disp($obj, 'phone') ?>">
          </div>
        </div>
        
        <!-- Text input-->
        <div class="form-group">
          <label class="col-md-3 control-label" for="email"><?php t(':EMAIL')?></label>
          <div class="col-md-9">
          <input id="email" name="email" placeholder="<?php t(':PLACEHOLDER_EMAIL') ?>" class="form-control input-sm" type="text" value="<?php disp($obj, 'email') ?>">
          </div>
        </div>
    
    <div class="text-right">
        <button type="submit" class="btn btn-primary">
            <?php t(':SAVE') ?> <i class="fa fa-arrow-circle-right"></i>
        </a>
    </div>
</form>