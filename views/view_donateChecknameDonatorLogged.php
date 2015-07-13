<p>
    <?php echo $obj['firstName'].' '.$obj['lastName'] ?>
</p>
<p>
    <?php echo $obj['addressLine1'] ?>
</p>
<?php if ($obj['addressLine2'] != '') { echo '<p>'.$obj['addressLine2'].'</p>'; } ?>
<p>
    <?php echo $obj['postalCode'].' '.$obj['city'].' - '.$obj['country'] ?>
</p>
<p></p>
<p>
    <?php t(':PHONE') ?>: <?php echo $obj['phone'] ?>
</p>
<p>
    <?php t(':MAIL') ?>: <?php echo $obj['email'] ?>
</p>
<a href="<?php echo url(array('controller' => 'user', 'view' => 'editUser', 'id' => $obj['id'], 'callback' =>
    url(array('controller' => 'donation', 'view' => 'donatecheckname'))
)) ?>" class="btn btn-default">
    <?php t(':UPDATE_USER_INFO') ?>
</a>
<a href="<?php echo url(array('controller' => 'user', 'action' => 'logout', 'callback' =>
    url(array('controller' => 'donation', 'view' => 'donatecheckname'))
)) ?>" class="btn btn-default">
    <?php t(':OTHER_USER') ?>
</a>
<div class="text-right">
    <a href="<?php echo url(array('controller' => 'donation', 'action' => 'confirm')) ?>" class="btn btn-primary">
        <?php t(':CONFIRM') ?> <i class="fa fa-arrow-circle-right"></i>
    </a>
</div>