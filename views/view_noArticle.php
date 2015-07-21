
    <section>
        <!-- BREADCRUMBS -->
        <div class="page-header">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-title"> <?php t(':NO_ARTICLE') ?> </h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- END BREADCRUMBS -->

        <!-- PAGE CONTENT -->
        <div class="page-content">
            <div class="container">
                <div class="row">
                    <div class="col-md-9">
                        <!-- BLOG SINGLE -->
                        <div class="blog single full-thumbnail">
                            <article>
                                <p><?php t(':NO_ARTICLE_CONTENT') ?></p>
                            </article>
                        </div>
                        <?php if (count($obj['article']['subArticles'])>0) { ?>
                        <hr/>
                        <div class="blog medium-thumbnail margin-bottom-30px">
                            <?php foreach($obj['article']['subArticles'] as $article)
                                renderPartial('news', $article);
                            ?>
                            <hr />
                        </div>
                        <?php } ?>
                        
                        <?php
                            if (isset($obj['embeddedArticle'])) {
                                renderPartial($obj['embeddedArticle'], $obj);
                            }
                        ?>
                    </div>
                    
                    <?php displayPartial('site', 'articleRightColumn', array()) ?>
                </div>
            </div>
        </div>
    </section>