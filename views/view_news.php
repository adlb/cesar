                        <article class="entry-post">
                            <header class="entry-header">
                                <h2 class="entry-title">
                                    <a href="<?php echo $obj['url'] ?>">
                                        <?php t($obj['titleKey'])?>
                                    </a>
                                </h2>
                            </header>
                            <div class="entry-content clearfix">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <figure class="featured-image">
                                            <a href="<?php echo $obj['url']; ?>">
                                                <div class="post-date-info clearfix">
                                                    <span class="post-month"><?php t(':MONTH_SHORT_'.strtoupper(date("M", strtotime($obj['date'])))) ?></span>
                                                    <span class="post-date"><?php echo date('j', strtotime($obj['date'])) ?></span>
                                                    <span class="post-year"><?php echo date('Y', strtotime($obj['date'])) ?></span>
                                                </div>
                                                <img src="<?php echo $obj['image']; ?>" class="img-responsive" alt="featured-image">
                                            </a>
                                        </figure>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="excerpt">
                                            <p style="overflow:hidden;text-overflow:ellipsis;height:120pt;">
                                                <?php echo str_replace(PHP_EOL, '<br />', $obj['textContent']) ?>
                                            </p>
                                            
                                            <p class="read-more pull-right">
                                                <a href="<?php echo $obj['url'] ?>" class="btn btn-primary"> <?php t(':READ_MORE') ?> <i class="fa fa-long-arrow-right"></i></a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>