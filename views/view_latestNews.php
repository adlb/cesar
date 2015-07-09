    
    <?php if (count($obj['latestNews']) > 0) { ?>
    <!-- LATEST NEWS -->
    <section>
        <div class="container">
            <h2 class="section-heading"><?php t(':LATEST NEWS') ?></h2>
            <div class="row" style="height:350px">
                <div class="col-md-4">
                    <div class="news-item news-featured">
                        <?php $first = $obj['latestNews'][0]; ?>
                        <a href="<?php echo $first['url'] ?>">
                            <img ng-src="<?php echo $first['image'] ?>" class="img-responsive" alt="News Thumbnail">
                        </a>
                        <h3 class="news-title"><a href="<?php echo $first['url'] ?>"><?php t($first['titleKey']) ?></a></h3>
                        <p style="overflow:hidden;text-overflow:ellipsis;height:45pt">
                            <?php echo str_replace(PHP_EOL, '<br />', $first['textContent']); ?>
                        </p>
                        <div class="news-meta">
                            <span class="news-datetime"><?php echo $first['date'] ?></span>
                            <span class="news-comment-count pull-right">
                                <ul class="list-inline">
                                <?php foreach($first['links'] as $link) { ?>
                                    <li>
                                        <a href="<?php echo $link['url'] ?>"><?php echo $link['type'] ?></a>
                                    </li>
                                <?php } ?>
                                </ul>
                            </span>	
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <?php 
                            for($i = 1; $i < count($obj['latestNews']); $i++) {
                                renderPartial('newsHome', $obj['latestNews'][$i]);
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php } ?>