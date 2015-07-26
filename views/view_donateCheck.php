<div class="row">
    <div class="col-md-6 col-md-offset-0 col-sm-8 col-sm-offset-2">
        <div class="well">
            <h3><?php t(':DONATION') ?></h3>
            <p><?php t(':AMOUNT') ?>&nbsp;: <?php echo number_format($_SESSION['currentDonation']['amount'], 2) ?> €</p>
            <p><?php t(':TYPE_PAYMENT') ?>&nbsp;: <?php t(':TYPE_PAYMENT_'.$_SESSION['currentDonation']['type']) ?></p>
            <div class="text-right">
                <a href="<?php echo url(array('controller' => 'donation', 'view' => 'donate')) ?>" class="btn btn-default">
                    <i class="fa fa-arrow-circle-left"></i> <?php t(':MODIFY') ?>
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-md-offset-0 col-sm-8 col-sm-offset-2">
        <div class="well">
            <h3>
                <?php t(':DONATOR') ?>
            </h3>
            <?php
                if (isset($obj['currentDonation']['saved']) && $obj['currentDonation']['saved']) {
                    renderPartial('donateCheckSaved', $obj['currentDonation']);
                } else {
                    renderPartial('donateCheckNotSaved', $obj['currentDonation']);
                }
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 text-right">
        <?php if (isset($obj['currentDonation']['saved']) && $obj['currentDonation']['saved']) { ?>
            <a href="<?php echo url(array('controller' => 'donation', 'action' => 'confirm')) ?>" class="btn btn-primary">
                <?php t(':DONATION_CONFIRM') ?> <i class="fa fa-arrow-circle-right"></i>
            </a>
        <?php } ?>
    </div>
</div>
