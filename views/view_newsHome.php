<ol class="breadcrumb">
    <li class="active">
        <a href="<?php echo url(array('controller' => 'site', 'view' => 'article', 'id' => $obj['id'])) ?>">
            <?php t($obj['titleKey'])?>
        </a>
    </li>
    
    <?php foreach($obj['links'] as $link) { ?>
        <a href="<?php echo $link['url'] ?>" class="pull-right">&nbsp;&nbsp;<?php echo $link['type'] ?></a>
    <?php } ?>
</ol>

<div class="ox_article">
    <?php echo $obj['htmlContent']; ?>
</div>
