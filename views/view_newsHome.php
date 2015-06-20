<div class="col-md-6 col-sm-6">
    <div class="news-item margin-bottom-30px clearfix">
        <a href="<?php echo $obj['url']?>"><img ng-src="<?php echo $obj['image']?>" class="img-responsive pull-left" alt="News Thumbnail"></a>
        <div class="right">
            <h3 class="news-title"><a href="<?php echo $obj['url']?>"><?php t($obj['titleKey']) ?></a></h3>
            <p><?php echo $obj['htmlContent']?></p>
        </div>
    </div>
</div>