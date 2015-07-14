<section>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php foreach($obj['alerts'] as $alert) { ?>
                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="alert<?php echo $alert['id'] ?>">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" href="#Dalert<?php echo $alert['id'] ?>">
                          <strong><?php t(':PREFIX_TITRE_ALERT') ?></strong> <?php t($alert['titleKey']); ?>
                        </a>
                      </h4>
                    </div>
                    <div id="Dalert<?php echo $alert['id'] ?>" class="panel-collapse collapse in" role="tabpanel">
                      <div class="panel-body">
                        <?php echo $alert['htmlContent'] ?>
                        <br/>
                        <p class="read-more">
                            <a href="<?php echo $alert['url'] ?>" class="btn btn-primary"> [<?php t(':READ_MORE') ?>] <i class="fa fa-long-arrow-right"></i></a>
                        </p>
                      </div>
                    </div>
                  </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>