<?php
    displayPartial('site', 'fixedArticle', array('titleKey' => 'DonateThanksForDonation'));
?>

<section>
    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-0 col-sm-8 col-sm-offset-2">
                    <div class="well">
                        <h3><?php t(':RESUME') ?></h3>
                        <p><?php t(':AMOUNT') ?>&nbsp;: <?php echo number_format($obj['donation']['amount'], 2) ?> €</p>
                        <p><?php t(':TYPE_PAYMENT') ?>&nbsp;: <?php t(':TYPE_PAYMENT'.$obj['donation']['type']) ?></p>
                        <h3><?php t(':DONATOR') ?></h3>
                        <p>
                            <?php echo $obj['donation']['firstName'].' '.$obj['donation']['lastName'] ?><br/>
                            <?php echo $obj['donation']['addressLine1'] ?><br/>
                        <?php if ($obj['donation']['addressLine2'] != '') { echo $obj['donation']['addressLine2'].'<br/>'; } ?>
                            <?php echo $obj['donation']['postalCode'].' '.$obj['donation']['city'].' - '.$obj['donation']['country'] ?><br/>
                        <br/>
                            <i class="fa fa-phone"></i>&nbsp;&nbsp;<?php echo $obj['donation']['phone'] ?><br/>
                            <i class="fa fa-envelope-o"></i>&nbsp;&nbsp;<?php echo $obj['donation']['email'] ?><br/>
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-md-offset-0 col-sm-8 col-sm-offset-2">
                    <div class="well">
                        <h3><?php t(':FINALIZE_YOUR_DONATION') ?></h3>
                        <?php
                            if ($obj['donation']['type'] == 'vir') {
                                displayPartial('site', 'fixedArticle', array('titleKey' => 'DonateFinalizeVir', 'raw' => true));
                            } elseif ($obj['donation']['type'] == 'cb') {
                                displayPartial('site', 'fixedArticle', array('titleKey' => 'DonateFinalizeCb', 'raw' => true));
                            } else {
                                displayPartial('site', 'fixedArticle', array('titleKey' => 'DonateFinalizeCb', 'raw' => true));
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php t(':RESUME_OF_PAIEMENT') ?>
<?php var_dump($obj['donation']) ?>