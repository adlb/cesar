<?php foreach($obj as $error) { ?>
<div class="alert alert-<?php echo $error['level'] ?> alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong><?php t($error['strongText']) ?></strong> <?php t($error['text']) ?>
</div>
<?php } ?>