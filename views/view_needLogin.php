<?php
    displayPartial('site', 'fixedArticle', array('titleKey' => 'CheckAdressBeforeDonate'));
?>

<?php
    displayPartial('user', 'login', array('callback' => url(array('controller' => 'donation', 'view' => 'donate'))));
?>