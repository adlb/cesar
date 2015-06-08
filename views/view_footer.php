<div class="footer">
    <p class="muted credit">
        <?php foreach($obj['links'] as $link) { ?>
            <a href="<?php echo $link['link']?>"><?php echo $link['display'] ?></a>
        <?php } ?>
    </p>
</div>
