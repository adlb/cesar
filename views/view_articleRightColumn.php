<div class="col-md-3">
    <div class="column-title">
        <div class="btn-donate" >
            <img src="templates/Repute/theme/assets/img/don_bg.jpg" />
            <button class="btn" onclick="location.href='<?php echo url(array('controller' => 'donation', 'view' => 'donate')) ?>';">
                <span><?php t(':MAKE_A_DONATION') ?><i class="fa fa-arrow-circle-right"></i></span>
            </button>
        </div>
    </div>
    <hr />
    <div>
        <h3><?php t(':NEWSLETTER') ?></h3>
        <p><?php t(':SUBSCRIBE_TO_OUR_NEWSLETTER') ?></p>

        <form method="POST" action="<?php echo url(array('controller' => 'user', 'action' => 'registerNewsLetter')) ?>">
            <div class="input-group input-group-lg">
                <input type="email" class="form-control" name="email" placeholder="<?php t(':PLACEHOLDER_EMAIL') ?>">
                <span class="input-group-btn">
                    <a class="btn btn-primary" type="submit"><span><?php t(':SUBSCRIBE')?></span></a>
                </span>
            </div>
            <div class="alert"></div>
        </form>
    </div>
    <hr />
    <h3><?php t(':SOME_LINKS') ?></h3>
    <ul>
    <?php foreach($obj['links'] as $link) { ?>
    <li>
        <a href="<?php echo $link['link']?>">
            <?php echo $link['display']?>
        </a>
    </li>
    <?php } ?>
    </ul>
</div>