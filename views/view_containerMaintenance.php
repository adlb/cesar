<!DOCTYPE html>
<head>
	<title><?php echo $obj['title'] ?></title>
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
</head>

<body style="margin-bottom:30px;">
	<!-- WRAPPER -->
	<div class="wrapper">
		
        <!-- NAVBAR -->
		<nav class="navbar navbar-default" role="navigation">
			<div class="container">
				<?php displayPartial('maintenance', 'menu', null); ?>
			</div>
		</nav>
		<!-- END NAVBAR -->
        
        <div class="page-content">
			<div class="container">
				<div class="row">
                    <div class="col-md-offset-3 col-md-6">
                        <?php displayPartial('site', 'fixedArticle', array('titleKey' => 'Maintenance_Page', 'renderType' => 'raw')); ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- FOOTER -->
        <footer class="hidden-print footer">
            <!-- COPYRIGHT -->
            <div class="text-center copyright">
                &copy;2015 adlb. All Rights Reserved. <a href="<?php echo url(array('controller' => 'user', 'view' => 'login')) ?>"><?php t(':LOGIN') ?></a>
            </div>
            <!-- END COPYRIGHT -->
        </footer>
        <!-- END FOOTER -->
    </div>
	<!-- END WRAPPER --><!-- JAVASCRIPTS -->
	<script src="templates/Repute/theme/assets/js/jquery-2.1.1.min.js"></script>
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
    <script>/*!
 * IE10 viewport hack for Surface/desktop Windows 8 bug
 * Copyright 2014-2015 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 */

// See the Getting Started docs for more information:
// http://getbootstrap.com/getting-started/#support-ie10-width

(function () {
  'use strict';

  if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
    var msViewportStyle = document.createElement('style')
    msViewportStyle.appendChild(
      document.createTextNode(
        '@-ms-viewport{width:auto!important}'
      )
    )
    document.querySelector('head').appendChild(msViewportStyle)
  }

})();</script>
</body>
</html>
