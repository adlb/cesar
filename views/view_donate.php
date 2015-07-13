<?php
    displayPartial('site', 'fixedArticle', array('titleKey' => 'Donate'));
?>
<section>
    <div class="page-content">
        <div class="container">
            <form method="post" class="form-horizontal" action="<?php echo url(array('controller' => 'donation', 'action' => 'donate')) ?>" >
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <label class="col-md-4 col-sm-12 control-label" for="amount"><?php t(':AMOUNT') ?></label>
                    <div class="col-md-5 col-sm-12">
                        <div class="input-group">
                            <span class="input-group-addon">€</span>
                            <input id="amount" name="amount" class="form-control" placeholder="montant" required="" type="text" value="<?php echo $obj['currentDonation']['amount'] ?>">
                        </div>
                        <p class="help-block"><?php t(':AMOUNT_HELP') ?></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="row-height">
                    <div class="col-md-2 col-md-offset-3 col-sm-8 col-sm-offset-2 col-height">
                        <div class="inside">
                            <div class="content">
                                <button type="submit" name="type" value="cb" class="btn btn-primary btn-money text-center">
                                    <P class="donate"><?php t(':CREDIT_CARD') ?></P>
                                    <P><i class="fa fa-credit-card"></i></P>
                                    <small><?php t(':VIA_CB_PARTNER') ?></small>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-md-offset-0 col-sm-8 col-sm-offset-2 col-height">
                        <div class="inside">
                            <div class="content text-center">
                                <button type="submit" name="type" value="vir" class="btn btn-primary btn-money text-center">
                                    <P><?php t(':VIREMENT') ?></P>
                                    <P><i class="fa fa-exchange"></i></P>
                                    <small>&nbsp;</small>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-md-offset-0 col-sm-8 col-sm-offset-2 col-height">
                        <div class="inside">
                            <div class="content">
                                <button type="submit" name="type" value="chq" class="btn btn-primary btn-money text-center">
                                    <P><?php t(':CHEQUE') ?></P>
                                    <P><i class="fa fa-pencil-square-o"></i></P>
                                    <small>&nbsp;</small>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</section>