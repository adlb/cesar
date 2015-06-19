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


            <!-- PAGE CONTENT -->
                <div class="row">
                    <!-- BLOG ENTRIES -->
                    <div class="blog medium-thumbnail margin-bottom-30px">
                        <!-- blog post -->
                        <article class="entry-post">
                            <header class="entry-header">
                                <h2 class="entry-title">
                                    <a href="<?php echo url(array('controller' => 'site', 'view' => 'article', 'id' => $obj['id'])) ?>">
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
                                            <a href="blog-single.html">
                                                <div class="post-date-info clearfix"><span class="post-month">DEC</span><span class="post-date">11</span><span class="post-year">2014</span></div>
                                                <img src="assets/img/blog/buildings-med.jpg" class="img-responsive" alt="featured-image">
                                            </a>
                                        </figure>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="excerpt">
                                            <p>Proactively reinvent team building customer service with ethical e-markets. Professionally utilize mission-critical technology whereas competitive solutions. Completely underwhelm go forward leadership without maintainable initiatives. Objectively disseminate customer directed e-commerce with prospective partnerships. Collaboratively actualize revolutionary total linkage before orthogonal catalysts for change. Appropriately facilitate optimal meta-services whereas end-to-end solutions...</p>
                                            <p class="read-more">
                                                <a href="#" class="btn btn-primary">Read More <i class="fa fa-long-arrow-right"></i></a>
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