    <!-- HERO UNIT -->
    <section class="hero-unit-animated">
        <div id="carousel-hero-animated" class="carousel">
            <!-- Slide Wrapper -->
            <div class="carousel-inner" role="listbox">
                <div class="item active">
                    <div class="container">
                        <div class="hero-left pull-left">
                            <div class="hero-text">
                                <h2 class="hero-heading animated fadeIn animation-delay-5"><?php t(':MAIN_TITLE') ?></h2>
                                <p class="lead animated fadeIn animation-delay-7"><?php t(':SUB_TITLE') ?></p>
                            </div>
                            <a href="<?php echo url(array('controller'=>'donation', 'view'=>'donate')) ?>" class="btn btn-info btn-lg hero-button animated fadeIn animation-delay-12"><?php t(':DONATE') ?></a>
                        </div>
                        <div class="hero-right pull-right">
                            <img src="templates/Repute/theme/assets/img/hero-unit-obj.png" class="animated fadeInRight animation-delay-9" alt="Repute Business Theme">
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Slide Wrapper -->
        </div>
    </section>
    <!-- END HERO UNIT -->

    <section>
        <?php displayPartial('site', 'alerts', $obj) ?>
    </section>
    
    <section>
        <!-- BREADCRUMBS -->
        <div class="page-header">
			<div class="container">
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
    	<!-- END BREADCRUMBS -->
        
        <!-- PAGE CONTENT -->
		<div class="page-content">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<!-- BLOG SINGLE -->
						<div class="blog single full-thumbnail">

                        <article class="cx_article">
                            <?php echo $obj['article']['htmlContent']; ?>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <?php displayPartial('site', 'fixedArticle', array('titleKey' => 'Home_BoxedContent', 'raw' => true)) ?>
    
    <?php displayPartial('site', 'latestNews', array()) ?>
    
    <?php displayPartial('site', 'fixedArticle', array('titleKey' => 'Home_Numbers', 'raw' => true)); ?>

    <?php displayPartial('site', 'fixedArticle', array('titleKey' => 'Home_Testimonials', 'raw' => true)); ?>

    <!-- CALL-TO-ACTION -->
    <section class="call-to-action">
        <div class="container">
            <div class="pull-left">
                <h2 class="section-heading"><?php t(':AQUESTION') ?></h2>
            </div>
            <div class="pull-right">
                <span><?php t(array(':SEND_A_MAIL_AT_{0}_OR', $obj['contact'])) ?></span>&nbsp;&nbsp;
                <a href="#" class="btn btn-lg btn-primary">CONTACT US</a>
            </div>
        </div>
    </section>
    <!-- END CALL-TO-ACTION -->

