<?php
    displayPartial('site', 'fixedArticle', array('titleKey' => 'CheckAdressBeforeDonate'));
?>

    <div class="container">
        <div class="row">
        <div class="col-md-6 col-md-offset-3">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?php t(':MY_PROFIL') ?></th>
                    <th>
                        <a class="pull-right" href="<?php echo url(array(
                            'controller' => 'user', 
                            'view' => 'editUser', 
                            'id' => $obj['user']['id'], 
                            'callback' => url(array(
                                    'controller' => 'donation', 
                                    'view' => 'donate'
                                    ))
                            ))?>" role="button">
                           <i class="fa fa-edit"></i>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row"><?php t(':EMAIL') ?></th>
                    <td><?php echo $obj['user']['email'] ?></td>
                </tr>
                <tr>
                    <th scope="row"><?php t(':FIRSTNAME') ?></th>
                    <td><?php echo $obj['user']['firstName'] ?></td>
                </tr>
                <tr>
                    <th scope="row"><?php t(':LASTNAME') ?></th>
                    <td><?php echo $obj['user']['lastName'] ?></td>
                </tr>
                <tr>
                    <th scope="row"><?php t(':ADDRESSLINE1') ?></th>
                    <td><?php echo $obj['user']['addressLine1'] ?></td>
                </tr>
                <tr>
                    <th scope="row"><?php t(':ADDRESSLINE2') ?></th>
                    <td><?php echo $obj['user']['addressLine2'] ?></td>
                </tr>
                <tr>
                    <th scope="row"><?php t(':POSTALCODE') ?></th>
                    <td><?php echo $obj['user']['postalCode'] ?></td>
                </tr>
                <tr>
                    <th scope="row"><?php t(':CITY') ?></th>
                    <td><?php echo $obj['user']['city'] ?></td>
                </tr>
                <tr>
                    <th scope="row"><?php t(':COUNTRY') ?></th>
                    <td><?php echo $obj['user']['country'] ?></td>
                </tr>
                <tr>
                    <th scope="row"><?php t(':PHONE') ?></th>
                    <td><?php echo $obj['user']['phone'] ?></td>
                </tr>
            </tbody>
        </table>
        </div>
        </div>
    

<form method="post" class="form-horizontal" action="<?php echo url(array('controller' => 'donation', 'action' => 'donate')) ?>" >
<input type="hidden" name="userid" value="<?php echo $obj['user']['id'] ?>" />
<input type="hidden" name="email" value="<?php echo $obj['user']['email'] ?>" />
<input type="hidden" name="firstName" value="<?php echo $obj['user']['firstName'] ?>" />
<input type="hidden" name="lastName" value="<?php echo $obj['user']['lastName'] ?>" />
<input type="hidden" name="addressLine1" value="<?php echo $obj['user']['addressLine1'] ?>" />
<input type="hidden" name="addressLine2" value="<?php echo $obj['user']['addressLine2'] ?>" />
<input type="hidden" name="city" value="<?php echo $obj['user']['city'] ?>" />
<input type="hidden" name="country" value="<?php echo $obj['user']['country'] ?>" />
<input type="hidden" name="postalCode" value="<?php echo $obj['user']['postalCode'] ?>" />
<input type="hidden" name="phone" value="<?php echo $obj['user']['phone'] ?>" />
    
<fieldset>

<!-- Form Name -->
<legend><?php t(':DONATION') ?></legend>

<!-- Prepended text-->
<div class="form-group">
  <label class="col-md-4 control-label" for="amount"><?php t(':AMOUNT') ?></label>
  <div class="col-md-5">
    <div class="input-group">
      <span class="input-group-addon">€</span>
      <input id="amount" name="amount" class="form-control" placeholder="montant" required="" type="text">
    </div>
    <p class="help-block"><?php t(':AMOUNT_HELP') ?></p>
  </div>
</div>

<!-- Multiple Radios -->
<div class="form-group">
  <label class="col-md-4 control-label" for="type"><?php t(':PAYMENT_TYPE') ?></label>
  <div class="col-md-4">
  <div class="radio">
    <label for="type-0">
      <input name="type" id="type-0" value="cb" checked="checked" type="radio">
      <?php t(':PAYMENT_TYPE_CB') ?>
    </label>
	</div>
  <div class="radio">
    <label for="type-1">
      <input name="type" id="type-1" value="chq" type="radio">
      <?php t(':PAYMENT_TYPE_CHQ') ?>
    </label>
	</div>
  <div class="radio">
    <label for="type-2">
      <input name="type" id="type-2" value="vir" type="radio">
      <?php t(':PAYMENT_TYPE_VIR') ?>
    </label>
	</div>
  </div>
</div>

<!-- Button (Double) -->
<div class="form-group">
  <label class="col-md-4 control-label" for="todo"></label>
  <div class="col-md-8">
    <button id="todo" name="todo" class="btn btn-primary">Validate</button>
    <a href="<?php echo url(array('controller' => 'site')) ?>" class="btn btn-default">Cancel</a>
  </div>
</div>

</fieldset>
</form>
</div>