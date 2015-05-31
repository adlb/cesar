<?php
    displayPartial('site', 'fixedArticle', array('titleKey' => 'CheckAdressBeforeDonate'));
?>
<a class="btn btn-default" href="<?php echo url(array('controller' => 'user', 'view' => 'login', 'callback' => 
    url(array('controller' => 'donation', 'view' => 'donate'))
)) ?>" role="button"><?php t(":I_HAVE_AN_ACCOUNT") ?></a>
<a class="btn btn-default" href="<?php echo url(array('controller' => 'user', 'view' => 'register', 'callback' =>
    url(array('controller' => 'donation', 'view' => 'donate'))
))?>" role="button"><?php t(":CREATE_AN_ACCOUNT") ?></a>
