
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
                    </div>
                    
                    <?php displayPartial('site', 'articleRightColumn', array()) ?>
                </div>
            </div>
        </div>
    </section>