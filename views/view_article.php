<ol class="breadcrumb">
  <li class="active">&nbsp;</li>
  <?php if ($obj['user']['role'] == 'Administrator') { ?>
    <a href="<?php echo url(array('controller' => 'builder', 'view' => 'editArticle', 'id' => $obj['article']['id'])) ?>"><span class="glyphicon glyphicon-pencil pull-right"></span></a>
    <a href="<?php echo url(array('controller' => 'builder', 'action' => 'deleteArticle', 'id' => $obj['article']['id'])) ?>"><span class="glyphicon glyphicon-trash pull-right"></span></a>
    <?php if ($obj['article']['status'] == 'show') { ?>
      <a href="<?php echo url(array('controller' => 'builder', 'action' => 'hideArticle', 'id' => $obj['article']['id'])) ?>"><span class="glyphicon glyphicon-eye-open pull-right"></span></a>
    <?php } else { ?>
      <a href="<?php echo url(array('controller' => 'builder', 'action' => 'showArticle', 'id' => $obj['article']['id'])) ?>"><span class="glyphicon glyphicon-eye-close pull-right"></span></a>
    <?php } ?>
  <?php } ?>
</ol>

<h1><?php t($obj['article']['title'])?></h1>
<div class="articleContent">
<?php echo $obj['article']['htmlContent']; ?>
</div>