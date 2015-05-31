<?php
    displayPartial('site', 'fixedArticle', array('titleKey' => 'ThanksForPaypal'));
?>

<?php t(':RESUME_OF_PAIEMENT') ?>
<?php var_dump($obj['donation']) ?>

<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
    <input type="hidden" name="cmd" value="_s-xclick" />
    <input type="hidden" name="hosted_button_id" value="<?php echo $obj['paypalId']?>" />
    <input type="hidden" name="address_override" value="1" />
    <input type="hidden" name="no_note" value="1" />
    <input type="hidden" name="custom" value="<?php echo $obj['donation']['id'] ?>" />
    <input type="hidden" name="first_name" value="<?php echo $obj['donation']['firstName'] ?>" />
    <input type="hidden" name="last_name" value="<?php echo $obj['donation']['lastName'] ?>" />
    <input type="hidden" name="address1" value="<?php echo $obj['donation']['addressLine1'] ?>" />
    <input type="hidden" name="address2" value="<?php echo $obj['donation']['addressLine2'] ?>" />
    <input type="hidden" name="city" value="<?php echo $obj['donation']['city'] ?>" />
    <input type="hidden" name="country" value="<?php echo $obj['donation']['country'] ?>" />
    <input type="hidden" name="zip" value="<?php echo $obj['donation']['postalCode'] ?>" />
    <input type="hidden" name="lc" value="<?php echo $obj['language'] ?>" />
    <input type="hidden" name="amount" value="<?php echo $obj['donation']['amount'] ?>" />
    <input type="hidden" name="charset" value="UTF-8" />
    <input type="hidden" name="return" value="<?php echo url(array('controller' => 'donation', 'action' => 'cancel', 'id' => $obj['donation']['id']), true) ?>" />
    <input type="image" src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus s?ris?!">
    <img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
</form>