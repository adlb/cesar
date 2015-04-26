<div>

<?php foreach($obj['alerts'] as $alert) { ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="alert<?php echo $alert['id'] ?>">
      <h4 class="panel-title">
        <a data-toggle="collapse" href="#Dalert<?php echo $alert['id'] ?>">
          <?php t($alert['titleKey']); ?>
        </a>
      </h4>
    </div>
    <div id="Dalert<?php echo $alert['id'] ?>" class="panel-collapse collapse in" role="tabpanel">
      <div class="panel-body">
        <?php echo $alert['htmlContent'] ?>
      </div>
    </div>
  </div>
<?php } ?>

</div>
