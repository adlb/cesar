    <!-- HERO UNIT -->
    <section class="hero-unit">
        <div id="carousel-hero">
            <div class="container">
                <div class="hero-left pull-left">
                    <div class="hero-text">
                        <h2 class="hero-heading"><?php t('HOME:MAIN_TITLE') ?></h2>
                        <p class="lead"><?php t('HOME:SUB_TITLE') ?></p>
                    </div>
                </div>
                <div class="hero-right pull-right">
                    <div class="btn-donate" onclick="location.href='<?php echo url(array('controller' => 'donation', 'view' => 'donate')) ?>';">
                        <img src="templates/Repute/theme/assets/img/don_bg.jpg" />
                        <button class="btn">
                            <span><?php t(':MAKE_A_DONATION') ?> <i class="fa fa-arrow-circle-right"></i></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END HERO UNIT -->

    <?php displayPartial('site', 'alerts', $obj) ?>
    
    <section>
        <!-- BREADCRUMBS -->
        <div class="page-header">
			<div class="container">
				<h1 class="page-title pull-left"> <?php echo $obj['article']['htmlTitle'] ?> </h1>
				<ol class="breadcrumb">
					<?php foreach($obj['article']['links'] as $link) { ?>
                    <li>
                        <a href="<?php echo $link['url'] ?>"><?php echo $link['name'] ?></a>
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
    
    <!-- BOXED CONTENT -->
    <section class="boxed-content-section">
        <div class="container">
            <div class="row">
                <?php for($i=0;$i<4;$i++) { ?>
                
                <div class="col-md-6">
                    <div class="boxed-content left-aligned left-boxed-icon">
                        <i class="fa fa-<?php t("HOME:BOXES_${i}_ICON") ?>"></i>
                        <h2 class="boxed-content-title"><?php t("HOME:BOXES_${i}_TITLE") ?></h2>
                        <p><?php t("HOME:BOXES_${i}_CONTENT") ?></p>
                    </div>
                </div>
                
                <?php } ?>
            </div>
        </div>
    </section>

    <!-- END BOXED CONTENT -->
    <?php displayPartial('site', 'latestNews', array()) ?>
    
    <!-- NUMBERS -->
    <section class="full-width-section no-margin">
        <div class="container">
            <div class="row">
                <?php for($i=0;$i<4;$i++) { ?>
                
                <div class="col-sm-3 col-xs-6">
                    <div class="number-info horizontal text-white-color">
                        <i class="fa fa-<?php t("HOME:NUMBERS_${i}_ICON") ?> pull-left"></i>
                        <p><?php t("HOME:NUMBERS_${i}_NUMBER") ?> <span><?php t("HOME:NUMBERS_${i}_TEXT") ?></span></p>
                    </div>
                </div>
                
                <?php } ?>
            </div>
        </div>
    </section>
    <!-- END NUMBERS -->
    <!-- TESTIMONIAL -->
    <section class="testimonial-with-bg parallax">
        <div class="container">
            <section class="testimonial">
                <div class="testimonial-body">
                    <p>"<?php t('HOME:TESTIMONIAL_PHRASE') ?>"</p>
                    <div class="testimonial-author">
                        <span><span class="author-name"><?php t('HOME:TESTIMONIAL_AUTHOR') ?></span> <em><?php t('HOME:TESTIMONIAL_QUALITY') ?></em></span>
                    </div>
                </div>
            </section>
        </div>
    </section>
    <!-- END TESTIMONIAL -->
        
    <!-- CALL-TO-ACTION -->
    <section class="call-to-action">
        <div class="container">
            <div class="pull-left">
                <h2 class="section-heading"><?php t(':A_QUESTION') ?></h2>
            </div>
            <div class="pull-right">
                <span><?php t(array(':SEND_A_MAIL_AT_{0}', $obj['contact'])) ?></span>
            </div>
        </div>
    </section>
    <!-- END CALL-TO-ACTION -->

