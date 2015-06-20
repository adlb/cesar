	<section>
        <!-- BREADCRUMBS -->
		<div class="page-header">
			<div class="container">
                <div class="row">
                    <h1 class="page-title pull-left"> <?php t($obj['article']['titleKey']) ?> </h1>
                    <ol class="breadcrumb">
                        <?php foreach($obj['article']['links'] as $link) { ?>
                        <li>
                            <a href="<?php echo $link['url'] ?>"><?php echo $link['type'] ?></a>
                        </li>
                        <?php } ?>
                    </ol>
                </div>
			</div>
		</div>
		<!-- END BREADCRUMBS -->

        <!-- PAGE CONTENT -->
		<div class="page-content">
			<div class="container">
				<div class="row">
					<div class="col-md-3 pull-left">
                        <img class="img-responsive" ng-src="<?php echo $obj['article']['image'] ?>" />
                    </div>
                    <div class="col-md-9">
						<!-- BLOG SINGLE -->
						<div class="blog single full-thumbnail">

                        <article class="cx_article">
                            <?php echo $obj['article']['htmlContent']; ?>
                        </article>

                        
                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php foreach($obj['article']['subArticles'] as $article)
                        renderPartial('news', $article);
                    ?>
                </div>
            </div>
        </div>
    </section>