<ol class="breadcrumb">
    <li class="active"><a href="<?php echo url(array('controller' => 'site', 'view' => 'article', 'id' => $obj['article']['id'])) ?>"><?php t($obj['article']['title'])?></a></li>
    
    <?php foreach($obj['article']['links'] as $link) { ?>
        <a href="<?php echo $link['url'] ?>" class="pull-right">&nbsp;&nbsp;<?php echo $link['type'] ?></a>
    <?php } ?>
</ol>

<div class="cx_article">
    <?php echo $obj['article']['htmlContent']; ?>
</div>

<?php foreach($obj['article']['subArticles'] as $article)
    renderPartial('news', $article);
?>
