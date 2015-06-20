
            <!-- PAGE CONTENT -->
                <div class="row">
                    <!-- BLOG ENTRIES -->
                    <div class="blog medium-thumbnail margin-bottom-30px">
                        <!-- blog post -->
                        <article class="entry-post">
                            <header class="entry-header">
                                <h2 class="entry-title">
                                    <a href="<?php echo $obj['url'] ?>">
                                        <?php t($obj['titleKey'])?>
                                    </a>
                                </h2>
                                <div class="meta-line clearfix">
                                    <div class="meta-tag-comment pull-right">
                                        <span class="post-tags">
                                            <?php foreach($obj['links'] as $link) { ?>
                                                <a href="<?php echo $link['url'] ?>"><?php echo $link['type'] ?></a>
                                            <?php } ?>
                                        </span>
                                    </div>
                                </div>
                            </header>
                            <div class="entry-content clearfix">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <figure class="featured-image">
                                            <a href="<?php echo $obj['url']; ?>">
                                                <div class="post-date-info clearfix">
                                                    <span class="post-month">DEC</span>
                                                    <span class="post-date">11</span>
                                                    <span class="post-year">2014</span>
                                                </div>
                                                <img src="<?php echo $obj['image']; ?>" class="img-responsive" alt="featured-image">
                                            </a>
                                        </figure>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="excerpt">
                                            <p><?php echo $obj['htmlContent'] ?></p>
                                            <p class="read-more">
                                                <a href="<?php echo $obj['url'] ?>" class="btn btn-primary"> <?php t('READ_MORE')?> <i class="fa fa-long-arrow-right"></i></a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                        <!-- end blog post -->
                        <hr />
                    </div>
                </div>