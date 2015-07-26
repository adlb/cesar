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
    <i class="fa fa-phone"></i>&nbsp;&nbsp;<?php echo $obj['phone'] ?>
</p>
<p>
    <i class="fa fa-envelope-o"></i>&nbsp;&nbsp;<?php echo $obj['email'] ?>
</p>
<div class="text-right">
<?php if (isset($obj['userId'])) { ?>
    <a href="<?php echo url(array('controller' => 'user', 'view' => 'editUser', 'id' => $obj['userId'], 'callback' =>
        url(array('controller' => 'donation', 'action' => 'donate', 'amount' => $obj['amount'], 'type' => $obj['type']))
    )) ?>" class="btn btn-default">
        <?php t(':UPDATE_USER_INFO') ?> <i class="fa fa-pencil"></i>
    </a>

    <a href="<?php echo url(array('controller' => 'user', 'action' => 'logout', 'callback' =>
        url(array('controller' => 'donation', 'action' => 'donate', 'amount' => $obj['amount'], 'type' => $obj['type']))
    )) ?>" class="btn btn-default">
        <?php t(':OTHER_USER') ?> <i class="fa fa-refresh"></i>
    </a>
<?php } else { ?>
    <a href="<?php echo url(array('controller' => 'donation', 'action' => 'unsaved')) ?>" class="btn btn-default">
        <?php t(':UPDATE_USER_INFO') ?> <i class="fa fa-pencil"></i>
    </a>
<?php } ?>

</div>