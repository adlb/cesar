<?php if (count($obj['alerts'])>0) { ?>
    <div id="modal-alerts" class="modal" tabindex="-1" role="dialog">
      <div class="modal-dialog custom-modal">
        <div class="modal-content">
          <div class="modal-body">
            <div id="carousel-example-generic" class="carousel slide alerts" data-ride="carousel">

              <!-- Wrapper for slides -->
              <div class="carousel-inner" role="listbox">
                <?php for($i = 0; $i < count($obj['alerts']); $i++) { ?>
                <div class="item<?php if ($i == 0) { echo " active"; } ?>">
                  <div class="row">
                    <div class="col-md-6 col-xs-12" style="text-align:center; font-size:2em;">
                        <b><?php echo $obj['alerts'][$i]['caption'] ?></b>
                    </div>
                    <div class="col-md-6 col-xs-12" style="text-align:center;">
                        <img src="<?php echo $obj['alerts'][$i]['image'] ?>"
                                class="img-thumbnail" width="100%">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 col-xs-6" style="text-align:center; font-size:2em;">
                        <a class="btn btn-primary" href="?controller=site&view=article&id=35">
                            <?php t(':READ_MORE') ?> <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                    <div class="col-md-6 col-xs-6" style="text-align:center; font-size:2em;">
                        <a class="btn btn-don" href="<?php echo url(array('controller' => 'donation', 'view' => 'donate')) ?>">
                            <?php t(':I_GIVE') ?> <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                  </div>
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php t(':CLOSE')?></button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
<?php } ?>
