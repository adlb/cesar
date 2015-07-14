
	<section>
        <!-- BREADCRUMBS -->
		<div class="page-header">
			<div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-title"> <?php echo $obj['article']['htmlTitle'] ?> </h1>
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
		</div>
		<!-- END BREADCRUMBS -->

        <!-- PAGE CONTENT -->
		<div class="page-content">
			<div class="container">
				<div class="row">
					<div class="col-md-9">
                        <?php if ($obj['article']['image'] != '') { ?>
						<div class="col-md-6 pull-left" style="padding-left:0;padding-bottom:3px;">
                            <img class="img-responsive" ng-src="<?php echo $obj['article']['image'] ?>" />
                        </div>
                        <?php } ?>
                        <!-- BLOG SINGLE -->
						<div class="blog single full-thumbnail">
                            <article>
                                <?php echo $obj['article']['htmlContent']; ?>
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
                    </div>
                    <div class="col-md-3">
                        <?php displayPartial('site', 'fixedArticle', array('titleKey' => 'Article_RightColumn', 'raw' => true)) ?>
					</div>
                </div>
            </div>
        </div>
    </section>