<!DOCTYPE html>
<head>
	<title>Home | <?php echo $obj['title'] ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Responsive Multipurpose Bootstrap Theme">
	<meta name="author" content="The Develovers">

	<!-- CSS -->
	<link href="templates/Repute/theme/assets/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="templates/Repute/theme/assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="templates/Repute/theme/assets/css/main.css" rel="stylesheet" type="text/css">

	<!-- IE 9 Fallback-->
	<!--[if IE 9]>
		<link href="templates/Repute/theme/assets/css/ie.css" rel="stylesheet">
	<![endif]-->

	<!-- GOOGLE FONTS -->
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400italic,400,600,700' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300,300italic,400italic,700,400,300' rel='stylesheet' type='text/css'>

	<!-- FAVICONS -->
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="templates/Repute/theme/assets/ico/repute144x144.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="templates/Repute/theme/assets/ico/repute114x114.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="templates/Repute/theme/assets/ico/repute72x72.png">
	<link rel="apple-touch-icon-precomposed" href="templates/Repute/theme/assets/ico/repute57x57.png">
	<link rel="shortcut icon" href="templates/Repute/theme/assets/ico/favicon.png">
	<script src="templates/Repute/theme/assets/js/jquery-2.1.1.min.js"></script>
<script>
var getScope = function(id, f) {
    if (typeof angular != 'undefined') {
        elm = angular.element(document.getElementById(id));
        if (typeof elm != 'undefined') {
            scope = elm.scope();
            if (typeof scope != 'undefined') {
                scope.$apply(f(scope));
                return;
            }
        }
    }
    window.setTimeout(function() {getScope(id, f);}, 0);
};
</script>
</head>

<body>
	<!-- WRAPPER -->
	<div class="wrapper" ng-app="cesarApp" ng-cloak>
		<!-- NAVBAR -->
		<nav class="navbar navbar-default" role="navigation">
			<div class="container">
				<?php displayPartial('site', 'menu', null); ?>
			</div>
		</nav>
		<!-- END NAVBAR -->

		
        <?php renderPartial('messages', $obj['messages']); ?>
    
    
        <?php renderPartial($obj['innerView'], $obj); ?>
        
        
        
        <!-- INTRO --
		<section>
			<div class="container">
				<div class="row">
					<div class="col-md-6">
						<h2 class="section-heading">KNOW BETTER</h2>
						<p class="lead">Synergistically procrastinate one-to-one results for accurate platforms. Assertively whiteboard corporate users whereas dynamic initiatives.</p>
						<p>Energistically architect leading-edge users and inexpensive internal or "organic" sources. Distinctively evolve e-business resources after progressive intellectual capital. Objectively architect maintainable technologies via strategic convergence. Continually optimize stand-alone expertise with e-business e-services. Continually conceptualize client-centered opportunities and.</p>
						<p>Dynamically customize backward-compatible processes via front-end models. Distinctively evisculate an expanded array of scenarios after virtual information. Professionally predominate technically sound resources through impactful mindshare. Efficiently drive viral interfaces without inexpensive value. Credibly.</p>
					</div>
					<div class="col-md-6">
						<img src="templates/Repute/theme/assets/img/intro-img.png" class="img-responsive" alt="Image Intro">
					</div>
				</div>
			</div>
		</section>
		<!-- END INTRO -->

		<!-- BOXED CONTENT --
		<section>
			<div class="container">
				<div class="row">
					<div class="col-md-6">
						<div class="boxed-content left-aligned left-boxed-icon">
							<i class="fa fa-flag"></i>
							<h2 class="boxed-content-title">GOAL ORIENTED</h2>
							<p>Holisticly harness just in time technologies via corporate scenarios. Intrinsicly predominate ubiquitous outsourcing through an expanded array of functionalities.</p>
						</div>
					</div>
					<div class="col-md-6">
						<div class="boxed-content left-aligned left-boxed-icon">
							<i class="fa fa-globe"></i>
							<h2 class="boxed-content-title">GLOBAL SERVICE</h2>
							<p>Holisticly harness just in time technologies via corporate scenarios. Intrinsicly predominate ubiquitous outsourcing through an expanded array of functionalities.</p>
						</div>
					</div>
					<div class="col-md-6">
						<div class="boxed-content left-aligned left-boxed-icon">
							<i class="fa fa-cog"></i>
							<h2 class="boxed-content-title">DYNAMIC CHANGE</h2>
							<p>Holisticly harness just in time technologies via corporate scenarios. Intrinsicly predominate ubiquitous outsourcing through an expanded array of functionalities.</p>
						</div>
					</div>
					<div class="col-md-6">
						<div class="boxed-content left-aligned left-boxed-icon">
							<i class="fa fa-users"></i>
							<h2 class="boxed-content-title">PROFESSIONAL SUPPORT</h2>
							<p>Holisticly harness just in time technologies via corporate scenarios. Intrinsicly predominate ubiquitous outsourcing through an expanded array of functionalities.</p>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- END BOXED CONTENT -->

		<!-- WHY REPUTE --
		<section>
			<div class="container">
				<h2 class="section-heading sr-only">WHY REPUTE</h2>
				<div class="tab-content">
					<div class="tab-pane fade in active text-center" id="tab-bottom1">
						<img src="templates/Repute/theme/assets/img/hero-unit-obj.png" class="img-responsive center-block margin-bottom-30px" alt="Ultra Responsive">
						<h3 class="text-accent-color">ULTRA RESPONSIVE</h3>
						<p class="lead">Phosfluorescently revolutionize viral leadership via turnkey technology. Synergistically monetize intermandated strategic theme areas through multimedia based.</p>
					</div>
					<div class="tab-pane fade text-center" id="tab-bottom2">
						<img src="templates/Repute/theme/assets/img/hero-unit-obj3.png" class="img-responsive center-block margin-bottom-30px" alt="Easy to Customize">
						<h3 class="text-accent-color">IT'S EASY TO CUSTOMIZE</h3>
						<p class="lead">Efficiently incentivize leading-edge alignments with go forward expertise. Conveniently myocardinate leveraged process improvements through progressive models.</p>
					</div>
					<div class="tab-pane fade text-center" id="tab-bottom3">
						<img src="templates/Repute/theme/assets/img/hero-unit-obj.png" class="img-responsive center-block margin-bottom-30px" alt="Clean and Elegant Design">
						<h3 class="text-accent-color">CLEAN &amp; ELEGANT DESIGN</h3>
						<p class="lead">Competently implement bricks-and-clicks collaboration and idea-sharing rather than visionary internal or "organic" sources. Rapidiously matrix premium core competencies for.</p>
					</div>
					<div class="tab-pane fade text-center" id="tab-bottom4">
						<img src="templates/Repute/theme/assets/img/free.png" class="img-responsive center-block margin-bottom-30px" alt="Free Updates and Support">
						<h3 class="text-accent-color">GET UPDATES &amp; SUPPORT FOR FREE</h3>
						<p class="lead">Dramatically supply adaptive imperatives and stand-alone content. Exceptional solutions after web-enabled potentialities. Synergistically negotiate alternative best practices whereas professional "outside the box" thinking.</p>
					</div>
				</div>
				<div class="custom-tabs-line tabs-line-top">
					<ul class="nav" role="tablist">
						<li class="active">
							<a href="#tab-bottom1" role="tab" data-toggle="tab">Responsive</a>
						</li>
						<li>
							<a href="#tab-bottom2" role="tab" data-toggle="tab">Easy to Customize</a>
						</li>
						<li>
							<a href="#tab-bottom3" role="tab" data-toggle="tab">Design</a>
						</li>
						<li>
							<a href="#tab-bottom4" role="tab" data-toggle="tab">Free Updates &amp; Support</a>
						</li>
					</ul>
				</div>
			</div>
		</section>
		<!-- END WHY REPUTE -->

		<!-- RECENT WORKS --
		<section class="recent-works">
			<div class="container">
				<h2 class="section-heading pull-left">RECENT WORKS</h2>
				<a href="#" class="btn btn-primary pull-right">See all works</a>
				<div class="clearfix"></div>
				<div class="portfolio-static">
					<div class="row">
						<div class="col-md-4">
							<div class="portfolio-item">
								<div class="overlay"></div>
								<div class="info">
									<h4 class="title">Raining</h4>
									<p class="brief-description">Photography</p>
									<a href="#" class="btn">read more</a>
								</div>
								<div class="media-wrapper">
									<img src="templates/Repute/theme/assets/img/portfolio/800x500/work5.png" alt="Item Thumbnail"/>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="portfolio-item">
								<div class="overlay"></div>
								<div class="info">
									<h4 class="title">Perfect Edge</h4>
									<p class="brief-description">Product Design</p>
									<a href="#" class="btn">read more</a>
								</div>
								<div class="media-wrapper">
									<img src="templates/Repute/theme/assets/img/portfolio/800x500/work6.png" alt="Item Thumbnail"/>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="portfolio-item">
								<div class="overlay"></div>
								<div class="info">
									<h4 class="title">Sunny Day</h4>
									<p class="brief-description">Photography</p>
									<a href="#" class="btn">read more</a>
								</div>
								<div class="media-wrapper">
									<img src="templates/Repute/theme/assets/img/portfolio/800x500/work7.png" alt="Item Thumbnail"/>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- END RECENT WORKS -->

		<!-- LATEST NEWS --
		<section>
			<div class="container">
				<h2 class="section-heading">LATEST NEWS</h2>
				<div class="row">
					<div class="col-md-4">
						<div class="news-item news-featured">
							<a href="#"><img src="templates/Repute/theme/assets/img/news/featured-news.png" class="img-responsive" alt="News Thumbnail"></a>
							<h3 class="news-title"><a href="#">In Demand: Collaboration Skill</a></h3>
							<p>Proactively engage orthogonal growth strategies without resource-leveling testing procedures. Seamlessly predominate interoperable growth strategies.</p>
							<div class="news-meta">
								<span class="news-datetime">23-02-2015</span>
								<span class="news-comment-count pull-right"><a href="#">65 Comments</a></span>
							</div>
						</div>
					</div>
					<div class="col-md-8">
						<div class="row">
							<div class="col-md-6 col-sm-6">
								<div class="news-item margin-bottom-30px clearfix">
									<a href="#"><img src="templates/Repute/theme/assets/img/news/news1.png" class="img-responsive pull-left" alt="News Thumbnail"></a>
									<div class="right">
										<h3 class="news-title"><a href="#">Growth Strategies We Must Know</a></h3>
										<p>Proactively engage orthogonal growth strategies without resource-leveling testing procedures.</p>
									</div>
								</div>
							</div>
							<div class="col-md-6 col-sm-6">
								<div class="news-item margin-bottom-30px clearfix">
									<a href="#"><img src="templates/Repute/theme/assets/img/news/news2.png" class="img-responsive pull-left" alt="News Thumbnail"></a>
									<div class="right">
										<h3 class="news-title"><a href="#">Alternative E-commerce</a></h3>
										<p>Proactively engage orthogonal growth strategies without resource-leveling testing procedures.</p>
									</div>
								</div>
							</div>
							<div class="col-md-6 col-sm-6">
								<div class="news-item margin-bottom-30px clearfix">
									<a href="#"><img src="templates/Repute/theme/assets/img/news/news3.png" class="img-responsive pull-left" alt="News Thumbnail"></a>
									<div class="right">
										<h3 class="news-title"><a href="#">Products Research Methodology &amp; Principles </a></h3>
										<p>Proactively engage orthogonal growth strategies without resource-leveling testing procedures.</p>
									</div>
								</div>
							</div>
							<div class="col-md-6 col-sm-6">
								<div class="news-item margin-bottom-30px clearfix">
									<a href="#"><img src="templates/Repute/theme/assets/img/news/news4.png" class="img-responsive pull-left" alt="News Thumbnail"></a>
									<div class="right">
										<h3 class="news-title"><a href="#">Understanding Globally Scale Quality Network</a></h3>
										<p>Proactively engage orthogonal growth strategies without resource-leveling testing procedures.</p>
									</div>
								</div>
							</div>
							<div class="col-md-6 col-sm-6">
								<div class="news-item margin-bottom-30px clearfix">
									<a href="#"><img src="templates/Repute/theme/assets/img/news/news5.png" class="img-responsive pull-left" alt="News Thumbnail"></a>
									<div class="right">
										<h3 class="news-title"><a href="#">Professional Leverage</a></h3>
										<p>Proactively engage orthogonal growth strategies without resource-leveling testing procedures.</p>
									</div>
								</div>
							</div>
							<div class="col-md-6 col-sm-6">
								<div class="see-all-news">
									<a href="#">See all news <i class="fa fa-long-arrow-right"></i></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- END LATEST NEWS -->

		<!-- NUMBERS --
		<section class="full-width-section no-margin">
			<div class="container">
				<div class="row">
					<div class="col-sm-3 col-xs-6">
						<div class="number-info horizontal text-white-color">
							<i class="fa fa-plug pull-left"></i>
							<p>5200 <span>SUBSCRIBERS</span></p>
						</div>
					</div>
					<div class="col-sm-3 col-xs-6">
						<div class="number-info horizontal text-white-color">
							<i class="fa fa-cubes pull-left"></i>
							<p>273 <span>PROJECTS</span></p>
						</div>
					</div>
					<div class="col-sm-3 col-xs-6">
						<div class="number-info horizontal text-white-color">
							<i class="fa fa-thumbs-up pull-left"></i>
							<p>640K <span>APPRECIATIONS</span></p>
						</div>
					</div>
					<div class="col-sm-3 col-xs-6">
						<div class="number-info horizontal text-white-color">
							<i class="fa fa-users pull-left"></i>
							<p>132 <span>CLIENTS</span></p>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- END NUMBERS -->

		<!-- TESTIMONIAL --
		<section class="testimonial-with-bg parallax">
			<div class="container">
				<section class="testimonial slick-carousel">
					<div class="testimonial-body">
						<p>Credibly extend parallel relationships after clicks-and-mortar content. Credibly pontificate team building alignments rather than diverse quality vectors.</p>
						<div class="testimonial-author">
							<img src="templates/Repute/theme/assets/img/user2.png" alt="Author" class="pull-left">
							<span><span class="author-name">Antonius</span> <em>CEO of TheCompany</em></span>
						</div>
					</div>
					<div class="testimonial-body">
						<p>Credibly pontificate team building alignments rather than diverse quality vectors. Monotonectally benchmark business communities for distinctive mindshare.</p>
						<div class="testimonial-author">
							<img src="templates/Repute/theme/assets/img/user1.png" alt="Author" class="pull-left">
							<span><span class="author-name">Michael</span> <em>General Manager of DreamCorp</em></span>
						</div>
					</div>
					<div class="testimonial-body">
						<p>Appropriately morph low-risk high-yield process improvements through progressive partnerships. Uniquely brand enabled. Globally network timely imperatives without plug-and-play schemas.</p>
						<div class="testimonial-author">
							<img src="templates/Repute/theme/assets/img/user5.png" alt="Author" class="pull-left">
							<span><span class="author-name">Palmer</span> <em>Freelance Web Developer</em></span>
						</div>
					</div>
				</section>
			</div>
		</section>
		<!-- END TESTIMONIAL -->

		<!-- OUR CLIENTS --
		<section class="clients">
			<div class="container">
				<h2 class="section-heading">OUR CLIENTS</h2>
				<ul class="list-inline list-client-logo">
					<li>
						<a href="#"><img src="templates/Repute/theme/assets/img/clients/logo1.png" alt="logo"></a>
					</li>
					<li>
						<a href="#"><img src="templates/Repute/theme/assets/img/clients/logo2.png" alt="logo"></a>
					</li>
					<li>
						<a href="#"><img src="templates/Repute/theme/assets/img/clients/logo3.png" alt="logo"></a>
					</li>
					<li>
						<a href="#"><img src="templates/Repute/theme/assets/img/clients/logo4.png" alt="logo"></a>
					</li>
					<li>
						<a href="#"><img src="templates/Repute/theme/assets/img/clients/logo5.png" alt="logo"></a>
					</li>
				</ul>
			</div>
		</section>
		<!-- END OUR CLIENTS -->
        
        <?php displayPartial('site', 'footer', $obj); ?>
    </div>
	<!-- END WRAPPER --><!-- JAVASCRIPTS -->
	<script src="templates/Repute/theme/assets/js/bootstrap.min.js"></script>
	<script src="templates/Repute/theme/assets/js/plugins/slick/slick.min.js"></script>
	<script src="templates/Repute/theme/assets/js/plugins/stellar/jquery.stellar.min.js"></script>
	<script src="templates/Repute/theme/assets/js/plugins/jquery-easypiechart/jquery.easypiechart.min.js"></script>
	<script src="templates/Repute/theme/assets/js/plugins/autohidingnavbar/jquery.bootstrap-autohidingnavbar.min.js"></script>
	<script src="templates/Repute/theme/assets/js/repute-scripts.js"></script>
    <script src="js/uicustom/jquery-ui.min.js"></script>
    <script src="js/angular.min.js"></script>
    <script src="js/ui-bootstrap-0.12.1.min.js"></script>
    <script src="js/smart-table.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
    <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/v4.0.0/src/js/bootstrap-datetimepicker.js"></script>
    <script src="js/cesar.js"></script>
</body>
</html>
