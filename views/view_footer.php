Footer : 
<?php foreach($obj['links'] as $link) { ?>
    <a href="<?php echo $link['link']?>"><?php echo $link['display'] ?></a>
<?php } ?>

<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample">
    Debug information
</button>
<div class="collapse" id="collapseExample">
    <div class="well">
        <?php var_dump($obj); ?>
    </div>
</div>

