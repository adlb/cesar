<h2><?php t($obj['title'])?></h2>

<?php foreach($obj['links'] as $link) { ?>
    <a href="<?php echo $link['url'] ?>"><?php echo $link['type'] ?></a>
<?php } ?>

<div class="ox_article">
<?php echo $obj['htmlContent']; ?>
</div>
