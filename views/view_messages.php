<?php foreach($obj as $message) { ?>
<div class="alert alert-<?php echo $message['level'] ?> alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong><?php t($message['strongText']) ?></strong> <?php t($message['text']) ?>
</div>
<?php } ?>