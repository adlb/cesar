<?php
    displayPartial('site', 'fixedArticle', array('titleKey' => 'CheckAdressBeforeDonate'));
?>

<?php t(':name') ?> : <?php echo $obj['user']['firstName'] . ' ' . $obj['user']['lastName'] ?><br/>
<?php t(':address') ?> : <?php echo $obj['user']['addressLine1'] . ' - ' . $obj['user']['addressLine2'] . ' - ' . $obj['user']['postalCode'] . ' ' . $obj['user']['city'] . ' - ' . $obj['user']['country'] ?><br/>

<a class="btn btn-default" href="<?php echo url(array(
    'controller' => 'user', 
    'view' => 'editUser', 
    'id' => $obj['user']['id'], 
    'callback' => url(array(
            'controller' => 'donation', 
            'view' => 'donate'
            ))
    ))?>" role="button"><?php t(":MODIFY_USER_INFO") ?></a><br/><br/>


<form method="post" action="<?php echo url(array('controller' => 'donation', 'action' => 'donate')) ?>" >
    <input type="text" name="amount" value="" /> €
    
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
    



<ul>
    <li>
        <button class="btn btn-lg btn-primary btn-block" type="submit" name="type" value="cb"><?php t(':DONATE_WITH_CB') ?></button>
    </li>
    <li>
        <button class="btn btn-lg btn-primary btn-block" type="submit" name="type" value="vir"><?php t(':DONATE_WITH_VIREMENT') ?></button>
    </li>
    <li>
        <button class="btn btn-lg btn-primary btn-block" type="submit" name="type" value="chq"><?php t(':DONATE_WITH_CHEQUE') ?></button>
    </li>
</ul>
</form>