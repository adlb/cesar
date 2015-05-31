<?php
    displayPartial('site', 'fixedArticle', array('titleKey' => 'Donation'));

?>
<a class="btn btn-default" href="<?php echo url(array('controller' => 'donation', 'view' => 'donate'))?>" role="button"><?php t(":DONATE") ?></a>
