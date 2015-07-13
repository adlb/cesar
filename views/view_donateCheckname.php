<?php
    displayPartial('site', 'fixedArticle', array('titleKey' => 'DonateCheckName'));
?>
<section>
    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-0 col-sm-8 col-sm-offset-2">
                    <div class="well">
                        <h3><?php t(':DONATION') ?></h3>
                        <p><?php t(':AMOUNT') ?>&nbsp;: <?php echo number_format($_SESSION['currentDonation']['amount'], 2) ?> €</p>
                        <p><?php t(':TYPE_PAYMENT') ?>&nbsp;: <?php t(':TYPE_PAYMENT'.$_SESSION['currentDonation']['type']) ?></p>
                        <div class="text-right">
                            <a href="<?php echo url(array('controller' => 'donation', 'view' => 'donate')) ?>" class="btn btn-primary">
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
                            if (isset($obj['user'])) {
                                renderPartial('donateChecknameDonatorLogged', $obj['user']);
                            } else {
                                renderPartial('donateChecknameDonatorNotLogged', $obj['currentDonation']);
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>