    <!-- HERO UNIT -->
    <section class="hero-unit-slider">
        <div id="carousel-hero" class="slick-carousel">
            <div class="carousel-inner" role="listbox">
                <div class="item active">
                    <img src="templates/Repute/theme/assets/img/sliders/slider1-h500.png" alt="Slider Image">
                    <div class="carousel-caption">
                        <h2 class="hero-heading">CLEAN &amp; ELEGANT DESIGN</h2>
                        <p class="lead">Giving valuable reputation and credibility to your business</p>
                        <a href="#" class="btn btn-lg hero-button">LEARN MORE</a>
                    </div>
                </div>
                <div class="item">
                    <img src="templates/Repute/theme/assets/img/sliders/slider2-h500.png" alt="Slider Image">
                    <div class="carousel-caption">
                        <h2 class="hero-heading">ULTRA RESPONSIVE</h2>
                        <p class="lead">Leave it to the theme, it knows how to deal with screen sizes</p>
                        <a href="#about" class="btn btn-lg hero-button">LEARN MORE</a>
                    </div>
                </div>
                <div class="item">
                    <img src="templates/Repute/theme/assets/img/sliders/slider3-h500.png" alt="Slider Image">
                    <div class="carousel-caption">
                        <h2 class="hero-heading">EASY TO CUSTOMIZE</h2>
                        <p class="lead">Readable code, well documented and FREE support</p>
                        <a href="#about" class="btn btn-lg hero-button">LEARN MORE</a>
                    </div>
                </div>
            </div>
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
					<div class="col-md-9">
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
    
    <?php displayPartial('site', 'latestNews', array()) ?>
    
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

